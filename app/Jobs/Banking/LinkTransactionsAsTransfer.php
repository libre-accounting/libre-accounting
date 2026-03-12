<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Traits\Categories;
use Illuminate\Support\Facades\DB;

class LinkTransactionsAsTransfer extends Job
{
    use Categories;

    protected Transaction $target;

    public function __construct(Transaction $source, int $target_id)
    {
        $this->model = $source;

        $this->target = Transaction::findOrFail($target_id);
    }

    public function handle(): Transfer
    {
        $this->checkPair();

        // The expense-typed transaction becomes the expense leg, the
        // income-typed one the income leg — independent of which the user
        // clicked. The Transfer derives its canonical amount/date from the
        // expense leg.
        [$expense, $income] = $this->model->isExpense()
            ? [$this->model, $this->target]
            : [$this->target, $this->model];

        return DB::transaction(function () use ($expense, $income) {
            $category_id = $this->getTransferCategoryId();

            // Retype the two existing rows in place. We update directly rather
            // than via UpdateTransaction, whose authorize() blocks transfer
            // types and reconciled rows.
            $expense->update([
                'type'        => Transaction::EXPENSE_TRANSFER_TYPE,
                'category_id' => $category_id,
                'contact_id'  => 0,
            ]);

            $income->update([
                'type'        => Transaction::INCOME_TRANSFER_TYPE,
                'category_id' => $category_id,
                'contact_id'  => 0,
            ]);

            return Transfer::create([
                'company_id'             => company_id(),
                'expense_transaction_id' => $expense->id,
                'income_transaction_id'  => $income->id,
                'created_from'           => 'core::link_transfer',
                'created_by'             => user()?->id,
            ]);
        });
    }

    /**
     * Guard the pair before linking. Throws so ajaxDispatch surfaces the
     * message to the user.
     */
    protected function checkPair(): void
    {
        $source = $this->model;
        $target = $this->target;

        if ($source->id === $target->id) {
            $this->reject(trans('transfers.errors.link_same_transaction'));
        }

        if ($source->company_id !== $target->company_id) {
            $this->reject(trans('transfers.errors.link_invalid'));
        }

        foreach ([$source, $target] as $transaction) {
            if ($transaction->isTransferTransaction()) {
                $this->reject(trans('transfers.errors.link_already_transfer'));
            }

            if ($transaction->reconciled) {
                $this->reject(trans('transfers.errors.link_reconciled'));
            }

            if (! empty($transaction->document_id)) {
                $this->reject(trans('transfers.errors.link_has_document'));
            }
        }

        if ($source->account_id === $target->account_id) {
            $this->reject(trans('transfers.errors.link_same_account'));
        }

        // Exactly one expense and one income (opposite directions).
        if ($source->isExpense() === $target->isExpense()) {
            $this->reject(trans('transfers.errors.link_same_direction'));
        }
    }

    protected function reject(string $message): void
    {
        throw new \Exception($message);
    }
}
