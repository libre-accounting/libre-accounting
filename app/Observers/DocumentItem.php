<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Models\Document\DocumentItem as Model;
use App\Utilities\Stock;

class DocumentItem extends Observer
{
    public function saved(Model $item)
    {
        if (! $item->wasRecentlyCreated && ! $item->wasChanged(['item_id', 'quantity', 'document_id'])) {
            return;
        }

        // Original values are still the pre-save ones inside this event
        if ($item->wasChanged('document_id') && $item->getOriginal('document_id')) {
            Stock::syncDocument((int) $item->getOriginal('document_id'));
        }

        if ($item->document_id) {
            Stock::syncDocument($item->document_id);
        }
    }

    /**
     * Fires for soft and force deletes alike.
     */
    public function deleted(Model $item)
    {
        if ($item->document_id) {
            Stock::syncDocument($item->document_id);
        }
    }
}
