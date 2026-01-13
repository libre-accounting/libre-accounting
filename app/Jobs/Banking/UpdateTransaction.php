<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionUpdated;
use App\Events\Banking\TransactionUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Banking\Transaction;

class UpdateTransaction extends Job implements ShouldUpdate
{
    public function handle(): Transaction
    {
        $this->authorize();

        event(new TransactionUpdating($this->model, $this->request));

        // Only set default type if no type is provided AND the model doesn't have a type
        $request_type = $this->request->get('type');

        if ($request_type !== null && ! array_key_exists($request_type, config('type.transaction'))) {
            // Invalid type provided, default to income type based on recurring frequency
            $type = (empty($this->request->get('recurring_frequency')) || ($this->request->get('recurring_frequency') == 'no')) ? Transaction::INCOME_TYPE : Transaction::INCOME_RECURRING_TYPE;

            $this->request->merge(['type' => $type]);
        } elseif ($request_type === null && $this->model->exists) {
            // No type provided but model exists, preserve existing type
            $this->request->merge(['type' => $this->model->type]);
        } elseif ($request_type === null) {
            // No type provided and new model, default to income
            $type = (empty($this->request->get('recurring_frequency')) || ($this->request->get('recurring_frequency') == 'no')) ? Transaction::INCOME_TYPE : Transaction::INCOME_RECURRING_TYPE;

            $this->request->merge(['type' => $type]);
        }

        \DB::transaction(function () {
            $this->model->update($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'transactions');

                    $this->model->attachMedia($media, 'attachment');
                }
            } elseif (! $this->request->file('attachment') && $this->model->attachment) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);
            }

            // Recurring
            $this->model->updateRecurring($this->request->all());
        });

        event(new TransactionUpdated($this->model, $this->request));

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if ($this->model->reconciled) {
            $message = trans('messages.warning.reconciled_tran');

            throw new \Exception($message);
        }

        if ($this->model->isTransferTransaction()) {
            throw new \Exception('Unauthorized');
        }
    }
}
