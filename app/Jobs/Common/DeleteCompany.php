<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\CompanyDeleted;
use App\Events\Common\CompanyDeleting;
use App\Interfaces\Job\ShouldDelete;
use App\Observers\Document as DocumentObserver;
use App\Observers\DocumentItem as DocumentItemObserver;
use App\Traits\Users;

class DeleteCompany extends Job implements ShouldDelete
{
    use Users;

    protected $current_company_id;

    public function booted(...$arguments): void
    {
        $this->current_company_id = company_id();
    }

    public function handle(): bool
    {
        $this->authorize();

        $this->model->makeCurrent();

        event(new CompanyDeleting($this->model, $this->current_company_id));

        // The ledger goes first and the whole company follows, so the stock observers have nothing to keep in step
        DocumentObserver::mute();
        DocumentItemObserver::mute();

        try {
            \DB::transaction(function () {
                $this->deleteRelationships($this->model, [
                    'item_stock_movements', 'item_components',
                    'accounts', 'document_histories', 'document_item_taxes', 'document_items', 'document_totals', 'documents', 'categories',
                    'contacts', 'currencies', 'dashboards', 'email_templates', 'items', 'module_histories', 'modules', 'reconciliations',
                    'recurring', 'reports', 'settings', 'taxes', 'transactions', 'transfers', 'widgets',
                ]);

                $this->model->delete();
            });
        } finally {
            DocumentObserver::unmute();
            DocumentItemObserver::unmute();
        }

        event(new CompanyDeleted($this->model, $this->current_company_id));

        company($this->current_company_id)->makeCurrent();

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can't delete active company
        if ($this->model->id == $this->current_company_id) {
            $message = trans('companies.error.delete_active');

            throw new \Exception($message);
        }

        // Check if user can access company
        if ($this->isNotUserCompany($this->model->id)) {
            $message = trans('companies.error.not_user_company');

            throw new \Exception($message);
        }
    }
}
