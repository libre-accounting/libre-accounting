<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Common\CreateItemStockAdjustment;
use App\Jobs\Common\CreateItemTaxes;
use App\Models\Common\Item;

class CreateItem extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Item
    {
        \DB::transaction(function () {
            $this->model = Item::create($this->request->all());

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'items');

                $this->model->attachMedia($media, 'picture');
            }

            $this->dispatch(new CreateItemTaxes($this->model, $this->request));

            // The opening count only applies to an item that starts out tracked
            if ($this->model->track_stock && $this->request->filled('stock_quantity')) {
                $this->dispatch(new CreateItemStockAdjustment($this->model, [
                    'quantity' => $this->request->get('stock_quantity'),
                    'description' => trans('stock.opening_count'),
                    'created_from' => $this->request->get('created_from'),
                    'created_by' => $this->request->get('created_by'),
                ]));
            }
        });

        return $this->model;
    }
}
