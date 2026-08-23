<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Jobs\Common\CreateItemStockAdjustment;
use App\Jobs\Common\CreateItemTaxes;
use App\Models\Common\Item;

class UpdateItem extends Job implements ShouldUpdate
{
    public function handle(): Item
    {
        \DB::transaction(function () {
            $this->model->update($this->request->all());

            // The item observer rebuilds the ledger when tracking is switched; the opening count only applies when it is switched on
            if ($this->model->wasChanged('track_stock') && $this->model->track_stock && $this->request->filled('stock_quantity')) {
                $this->dispatch(new CreateItemStockAdjustment($this->model, [
                    'quantity' => $this->request->get('stock_quantity'),
                    'description' => trans('stock.opening_count'),
                    'created_from' => $this->request->get('created_from'),
                    'created_by' => $this->request->get('created_by'),
                ]));
            }

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'items');

                $this->model->attachMedia($media, 'picture');
            }

            $this->deleteRelationships($this->model, ['taxes']);

            $this->dispatch(new CreateItemTaxes($this->model, $this->request));
        });

        return $this->model;
    }
}
