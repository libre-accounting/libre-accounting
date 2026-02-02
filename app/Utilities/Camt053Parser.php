<?php

namespace App\Utilities;

use Illuminate\Support\Str;

/**
 * Parses CAMT.053 (ISO 20022 Bank-to-Customer Statement) XML into normalized
 * arrays. Pure PHP, no framework dependencies, so it can be unit-tested in
 * isolation. Uses SimpleXML only (ext-xml / ext-dom are required by the app).
 *
 * Supports camt.053.001.02 through .08 by stripping the versioned namespace
 * before querying, so element paths work regardless of schema version.
 *
 * Returns:
 * [
 *   'statement' => [
 *     'statement_id', 'iban', 'currency_code',
 *     'opening_balance', 'closing_balance',
 *     'statement_from', 'statement_to',
 *   ],
 *   'lines' => [
 *     [
 *       'type'              => 'income'|'expense',
 *       'booked_at'         => 'Y-m-d H:i:s'|null,
 *       'valued_at'         => 'Y-m-d H:i:s'|null,
 *       'amount'            => float (absolute),
 *       'currency_code'     => 'EUR',
 *       'bank_reference'    => string|null,
 *       'end_to_end_id'     => string|null,
 *       'counterparty_name' => string|null,
 *       'counterparty_iban' => string|null,
 *       'remittance_info'   => string|null,
 *     ],
 *     ...
 *   ],
 * ]
 */
class Camt053Parser
{
    public const TYPE_INCOME = 'income';
    public const TYPE_EXPENSE = 'expense';

    /**
     * Parse raw CAMT.053 XML content.
     *
     * @throws \App\Utilities\Camt053ParseException
     */
    public static function parse(string $contents): array
    {
        $xml = self::load($contents);

        $statement = $xml->xpath('//BkToCstmrStmt/Stmt');

        if (empty($statement)) {
            throw new Camt053ParseException('No statement (BkToCstmrStmt/Stmt) found in the CAMT.053 file.');
        }

        // Only the first statement is parsed; callers validate the account/IBAN.
        $stmt = $statement[0];

        return [
            'statement' => self::parseStatement($stmt),
            'lines'     => self::parseLines($stmt),
        ];
    }

    /**
     * Load and namespace-normalize the XML document.
     *
     * @throws \App\Utilities\Camt053ParseException
     */
    protected static function load(string $contents): \SimpleXMLElement
    {
        $contents = trim($contents);

        if ($contents === '') {
            throw new Camt053ParseException('The file is empty.');
        }

        // Strip the default ISO 20022 namespace so unprefixed XPath works
        // across camt.053 schema versions (.02 / .04 / .08).
        $contents = preg_replace('/(<\/?)[A-Za-z0-9]+:/', '$1', $contents);
        $contents = preg_replace('/\sxmlns(:[A-Za-z0-9]+)?="[^"]*"/', '', $contents);

        $previous = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $xml = simplexml_load_string($contents, \SimpleXMLElement::class, LIBXML_NONET);

        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if ($xml === false) {
            $message = !empty($errors) ? trim($errors[0]->message) : 'Unknown XML error.';

            throw new Camt053ParseException('The file is not valid XML: ' . $message);
        }

        return $xml;
    }

    protected static function parseStatement(\SimpleXMLElement $stmt): array
    {
        return [
            'statement_id'    => self::firstString($stmt, 'Id'),
            'iban'            => self::firstString($stmt, 'Acct/Id/IBAN'),
            'currency_code'   => self::firstString($stmt, 'Acct/Ccy'),
            'opening_balance' => self::balance($stmt, ['OPBD', 'PRCD']),
            'closing_balance' => self::balance($stmt, ['CLBD']),
            'statement_from'  => self::date(self::first($stmt, 'FrToDt/FrDtTm') ?? self::first($stmt, 'FrToDt/FrDt')),
            'statement_to'    => self::date(self::first($stmt, 'FrToDt/ToDtTm') ?? self::first($stmt, 'FrToDt/ToDt')),
        ];
    }

    /**
     * Parse all entries into one line per transaction detail (splitting
     * batched entries that carry multiple TxDtls).
     */
    protected static function parseLines(\SimpleXMLElement $stmt): array
    {
        $lines = [];

        foreach ($stmt->Ntry as $entry) {
            $entryType = self::creditDebit((string) ($entry->CdtDbtInd ?? ''));
            $bookedAt  = self::date(self::first($entry, 'BookgDt/DtTm') ?? self::first($entry, 'BookgDt/Dt'));
            $valuedAt  = self::date(self::first($entry, 'ValDt/DtTm') ?? self::first($entry, 'ValDt/Dt'));
            $bankRef   = self::firstString($entry, 'AcctSvcrRef');

            $details = $entry->xpath('NtryDtls/TxDtls');

            // No transaction details: emit a single line from the entry header.
            if (empty($details)) {
                $lines[] = self::buildLine([
                    'type'          => $entryType,
                    'booked_at'     => $bookedAt,
                    'valued_at'     => $valuedAt,
                    'amount'        => self::decimal((string) ($entry->Amt ?? '0')),
                    'currency_code' => self::amountCurrency($entry->Amt ?? null),
                    'bank_reference' => $bankRef,
                ]);

                continue;
            }

            foreach ($details as $tx) {
                // A TxDtls may override the entry-level credit/debit indicator.
                $txType = self::creditDebit((string) ($tx->CdtDbtInd ?? '')) ?? $entryType;

                // Prefer the TxDtls amount (batched entries), else entry amount.
                $amountNode = $tx->Amt ?? ($tx->AmtDtls->TxAmt->Amt ?? null) ?? $entry->Amt ?? null;

                $lines[] = self::buildLine([
                    'type'              => $txType,
                    'booked_at'         => $bookedAt,
                    'valued_at'         => $valuedAt,
                    'amount'            => self::decimal((string) ($amountNode ?? '0')),
                    'currency_code'     => self::amountCurrency($amountNode),
                    'bank_reference'    => self::firstString($tx, 'Refs/AcctSvcrRef') ?? $bankRef,
                    'end_to_end_id'     => self::firstString($tx, 'Refs/EndToEndId'),
                    'counterparty_name' => self::counterpartyName($tx, $txType),
                    'counterparty_iban' => self::counterpartyIban($tx, $txType),
                    'remittance_info'   => self::remittance($tx),
                ]);
            }
        }

        return $lines;
    }

    protected static function buildLine(array $attributes): array
    {
        return array_merge([
            'type'              => self::TYPE_EXPENSE,
            'booked_at'         => null,
            'valued_at'         => null,
            'amount'            => 0.0,
            'currency_code'     => null,
            'bank_reference'    => null,
            'end_to_end_id'     => null,
            'counterparty_name' => null,
            'counterparty_iban' => null,
            'remittance_info'   => null,
        ], array_filter($attributes, fn ($value) => $value !== null));
    }

    /**
     * Map the ISO 20022 credit/debit indicator to an Akaunting transaction type.
     */
    protected static function creditDebit(string $indicator): ?string
    {
        return match (strtoupper(trim($indicator))) {
            'CRDT'  => self::TYPE_INCOME,
            'DBIT'  => self::TYPE_EXPENSE,
            default => null,
        };
    }

    /**
     * For income the counterparty is the debtor (who paid us); for expense it
     * is the creditor (whom we paid).
     */
    protected static function counterpartyName(\SimpleXMLElement $tx, string $type): ?string
    {
        $party = $type === self::TYPE_INCOME ? 'Dbtr' : 'Cdtr';

        return self::firstString($tx, "RltdPties/{$party}/Nm")
            ?? self::firstString($tx, "RltdPties/{$party}/Pty/Nm");
    }

    protected static function counterpartyIban(\SimpleXMLElement $tx, string $type): ?string
    {
        $account = $type === self::TYPE_INCOME ? 'DbtrAcct' : 'CdtrAcct';

        return self::firstString($tx, "RltdPties/{$account}/Id/IBAN");
    }

    /**
     * Concatenate unstructured remittance lines, falling back to the
     * structured creditor reference.
     */
    protected static function remittance(\SimpleXMLElement $tx): ?string
    {
        $parts = [];

        foreach ((array) $tx->xpath('RmtInf/Ustrd') as $node) {
            $value = trim((string) $node);

            if ($value !== '') {
                $parts[] = $value;
            }
        }

        foreach ((array) $tx->xpath('RmtInf/Strd/CdtrRefInf/Ref') as $node) {
            $value = trim((string) $node);

            if ($value !== '') {
                $parts[] = $value;
            }
        }

        $remittance = trim(implode(' ', $parts));

        return $remittance !== '' ? $remittance : null;
    }

    /**
     * Find the first matching balance amount, signed by its credit/debit
     * indicator, for any of the given ISO balance type codes.
     */
    protected static function balance(\SimpleXMLElement $stmt, array $codes): ?float
    {
        foreach ($stmt->Bal as $bal) {
            $code = self::firstString($bal, 'Tp/CdOrPrtry/Cd');

            if ($code === null || !in_array(strtoupper($code), $codes, true)) {
                continue;
            }

            $amount = self::decimal((string) ($bal->Amt ?? '0'));
            $sign = self::creditDebit((string) ($bal->CdtDbtInd ?? 'CRDT'));

            return $sign === self::TYPE_EXPENSE ? -$amount : $amount;
        }

        return null;
    }

    protected static function amountCurrency($amountNode): ?string
    {
        if ($amountNode === null) {
            return null;
        }

        $attributes = $amountNode->attributes();

        return isset($attributes['Ccy']) ? (string) $attributes['Ccy'] : null;
    }

    /**
     * Normalize an ISO date or date-time string to 'Y-m-d H:i:s'.
     */
    protected static function date(?string $value): ?string
    {
        $value = $value !== null ? trim($value) : '';

        if ($value === '') {
            return null;
        }

        try {
            return Date::parse($value)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected static function decimal(string $value): float
    {
        $value = trim($value);

        return $value === '' ? 0.0 : (float) $value;
    }

    /**
     * Resolve a relative XPath to its first node, or null.
     */
    protected static function first(\SimpleXMLElement $node, string $path): ?\SimpleXMLElement
    {
        $found = $node->xpath($path);

        return !empty($found) ? $found[0] : null;
    }

    protected static function firstString(\SimpleXMLElement $node, string $path): ?string
    {
        $found = self::first($node, $path);

        if ($found === null) {
            return null;
        }

        $value = trim((string) $found);

        return $value !== '' ? Str::limit($value, 65535, '') : null;
    }
}
