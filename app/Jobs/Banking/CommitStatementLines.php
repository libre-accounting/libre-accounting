<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine;
use App\Models\Banking\Transaction;
use App\Models\Setting\Currency;
use App\Traits\Transactions;
use Illuminate\Support\Facades\DB;

class CommitStatementLines extends Job
{
    use Transactions;

    protected BankStatementImport $statementImport;

    protected array $line_ids;

    public function __construct(BankStatementImport $statementImport, array $line_ids)
    {
        $this->statementImport = $statementImport;
        $this->line_ids = $line_ids;
    }

    public function handle(): int
    {
        $created = 0;

        $lines = $this->statementImport->lines()
            ->pending()
            ->whereIn('id', $this->line_ids)
            ->get();

        foreach ($lines as $line) {
            // Skip lines that already produced a transaction (idempotent re-commit).
            if (! empty($line->transaction_id)) {
                continue;
            }

            // Transaction-level dedup: don't create a duplicate of an existing
            // transaction for the same account/date/amount/reference.
            if ($this->transactionExists($line)) {
                $line->update(['status' => BankStatementLine::STATUS_SKIPPED]);

                continue;
            }

            $transaction = $this->dispatch(new CreateTransaction($this->buildRequest($line)));

            $line->update([
                'transaction_id' => $transaction->id,
                'status'         => BankStatementLine::STATUS_IMPORTED,
            ]);

            $created++;
        }

        $this->refreshImportStatus();

        return $created;
    }

    /**
     * Build the request array consumed by the CreateTransaction job.
     */
    protected function buildRequest(BankStatementLine $line): array
    {
        return [
            'company_id'     => company_id(),
            'type'           => $line->type,
            'number'         => $this->getNextTransactionNumber($line->type),
            'account_id'     => $line->account_id,
            'paid_at'        => optional($line->booked_at ?: $line->valued_at)->format('Y-m-d H:i:s'),
            'amount'         => $line->amount,
            'currency_code'  => $line->currency_code,
            'currency_rate'  => $this->getCurrencyRate($line->currency_code),
            'document_id'    => $line->document_id,
            'contact_id'     => $line->contact_id,
            'category_id'    => $line->category_id,
            'payment_method' => $line->payment_method ?: setting('default.payment_method'),
            'reference'      => $line->bank_reference,
            'description'    => $line->description,
            'created_from'   => 'core::camt_import',
        ];
    }

    protected function getCurrencyRate(string $code): float
    {
        $currency = Currency::where('code', $code)->first();

        return (float) ($currency->rate ?? config('money.currencies.' . $code . '.rate', 1));
    }

    /**
     * Has a transaction already been booked for this statement line's
     * account/date/amount/reference? Mirrors the import hasRow() precedent.
     */
    protected function transactionExists(BankStatementLine $line): bool
    {
        $query = Transaction::where('account_id', $line->account_id)
            ->where('type', $line->type)
            ->where('amount', $line->amount);

        if ($line->booked_at) {
            $query->whereDate('paid_at', $line->booked_at->format('Y-m-d'));
        }

        if ($line->bank_reference) {
            $query->where('reference', $line->bank_reference);
        }

        return $query->exists();
    }

    /**
     * Update the parent run's imported count and overall status.
     */
    protected function refreshImportStatus(): void
    {
        $imported = $this->statementImport->lines()
            ->where('status', BankStatementLine::STATUS_IMPORTED)
            ->count();

        $remaining = $this->statementImport->lines()
            ->where('status', BankStatementLine::STATUS_PENDING)
            ->count();

        $this->statementImport->update([
            'imported_lines' => $imported,
            'status'         => $remaining === 0 ? 'completed' : 'reviewing',
        ]);
    }
}
