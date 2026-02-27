<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use Illuminate\Support\Facades\DB;

class DeleteBankStatementImport extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        DB::transaction(function () {
            // Soft-delete the staged lines, then the run itself. Transactions
            // already created from imported lines are intentionally kept.
            $this->model->lines()->delete();

            $this->model->delete();
        });

        return true;
    }
}
