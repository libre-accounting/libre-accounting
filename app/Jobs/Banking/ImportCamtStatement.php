<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine;
use App\Utilities\Camt053Parser;
use App\Utilities\Camt053ParseException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ImportCamtStatement extends Job
{
    protected UploadedFile $file;

    protected int $account_id;

    protected ?string $filename;

    public function __construct(UploadedFile $file, int $account_id)
    {
        $this->file = $file;
        $this->account_id = $account_id;
        $this->filename = $file->getClientOriginalName();
    }

    /**
     * @throws \App\Utilities\Camt053ParseException
     */
    public function handle(): BankStatementImport
    {
        $contents = file_get_contents($this->file->getRealPath());

        if ($contents === false) {
            throw new Camt053ParseException(trans('statement_imports.errors.unreadable'));
        }

        $file_hash = hash('sha256', $contents);

        // Guard against re-uploading the exact same file.
        $existing = BankStatementImport::where('file_hash', $file_hash)->first();

        if ($existing) {
            throw new Camt053ParseException(trans('statement_imports.errors.already_imported'));
        }

        $parsed = Camt053Parser::parse($contents);

        return DB::transaction(function () use ($parsed, $file_hash) {
            $statement = $parsed['statement'];
            $lines = $parsed['lines'];

            $this->model = BankStatementImport::create([
                'company_id'      => company_id(),
                'account_id'      => $this->account_id,
                'filename'        => $this->filename,
                'statement_id'    => $statement['statement_id'],
                'iban'            => $statement['iban'],
                'currency_code'   => $statement['currency_code'],
                'opening_balance' => $statement['opening_balance'],
                'closing_balance' => $statement['closing_balance'],
                'statement_from'  => $statement['statement_from'],
                'statement_to'    => $statement['statement_to'],
                'total_lines'     => count($lines),
                'imported_lines'  => 0,
                'status'          => 'reviewing',
                'file_hash'       => $file_hash,
                'created_from'    => 'core::camt_import',
                'created_by'      => user_id(),
            ]);

            foreach ($lines as $line) {
                $hash = $this->lineHash($line);

                $status = $this->isDuplicate($hash)
                    ? BankStatementLine::STATUS_DUPLICATE
                    : BankStatementLine::STATUS_PENDING;

                BankStatementLine::create([
                    'company_id'               => company_id(),
                    'bank_statement_import_id' => $this->model->id,
                    'account_id'               => $this->account_id,
                    'type'                     => $line['type'],
                    'booked_at'                => $line['booked_at'],
                    'valued_at'                => $line['valued_at'],
                    'amount'                   => $line['amount'],
                    'currency_code'            => $line['currency_code'] ?: $statement['currency_code'],
                    'bank_reference'           => $line['bank_reference'],
                    'end_to_end_id'            => $line['end_to_end_id'],
                    'counterparty_name'        => $line['counterparty_name'],
                    'counterparty_iban'        => $line['counterparty_iban'],
                    'remittance_info'          => $line['remittance_info'],
                    'description'              => $this->describe($line),
                    'payment_method'           => setting('default.payment_method'),
                    'status'                   => $status,
                    'hash'                     => $hash,
                    'created_from'             => 'core::camt_import',
                    'created_by'               => user_id(),
                ]);
            }

            return $this->model;
        });
    }

    /**
     * Deterministic per-line hash for dedup across re-imports.
     */
    protected function lineHash(array $line): string
    {
        $reference = $line['end_to_end_id'] ?: ($line['bank_reference'] ?: $line['remittance_info']);

        return hash('sha256', implode('|', [
            $this->account_id,
            $line['type'],
            $line['booked_at'],
            number_format((float) $line['amount'], 4, '.', ''),
            $reference,
        ]));
    }

    /**
     * A line is a duplicate if an identical hash already exists for the company.
     */
    protected function isDuplicate(string $hash): bool
    {
        return BankStatementLine::where('hash', $hash)->exists();
    }

    /**
     * Compose a human-readable description from the available CAMT fields.
     */
    protected function describe(array $line): string
    {
        $parts = array_filter([
            $line['counterparty_name'],
            $line['remittance_info'],
        ]);

        return implode(' - ', $parts);
    }
}
