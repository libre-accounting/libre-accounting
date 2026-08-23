<?php

namespace App\Observers;

use App\Abstracts\Observer;
use App\Models\Common\Item as Model;
use App\Utilities\Stock;

class Item extends Observer
{
    /**
     * Switching tracking on backfills the ledger from past documents and
     * switching it off clears it, whichever write path flipped the flag.
     * A freshly created item reports no change and has no documents yet.
     */
    public function saved(Model $item)
    {
        if (! $item->wasChanged('track_stock')) {
            return;
        }

        Stock::resyncItem($item);
    }
}
