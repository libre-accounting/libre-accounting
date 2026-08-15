<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use App\Utilities\Stock;

/**
 * Removes a part from an item's recipe.
 */
class DeleteItemComponent extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        \DB::transaction(function () {
            $item = $this->model->item()->first();

            $this->model->delete();

            // Documents that sold this item as a kit no longer consume the part
            if ($item) {
                Stock::resyncItem($item);
            }
        });

        return true;
    }
}
