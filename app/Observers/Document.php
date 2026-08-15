<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Models\Document\Document as Model;
use App\Utilities\Stock;

class Document extends Observer
{
    /**
     * The line observer already derives movements from the stored document
     * row, so only a change to a column that derivation reads needs a resync.
     */
    public function saved(Model $document)
    {
        if (! $document->wasChanged(['status', 'issued_at', 'type'])) {
            return;
        }

        Stock::syncDocument($document->id);
    }

    public function deleted(Model $document)
    {
        Stock::syncDocument($document->id);
    }

    public function restored(Model $document)
    {
        Stock::syncDocument($document->id);
    }
}
