<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Models\Document\Document;

class CancelDocument extends Job
{
    protected $model;

    public function __construct(Document $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }

    public function handle(): Document
    {
        \DB::transaction(function () {
            // Disconnect transactions instead of deleting them
            // Set document_id to null so they become standalone transactions
            $this->model->transactions()->update(['document_id' => null]);

            // Delete recurring relationship
            $this->deleteRelationships($this->model, [
                'recurring'
            ]);

            $this->model->status = 'cancelled';
            $this->model->save();
        });

        return $this->model;
    }
}
