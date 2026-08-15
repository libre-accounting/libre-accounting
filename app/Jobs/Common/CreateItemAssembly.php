<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Item;
use App\Models\Common\ItemStockMovement;

/**
 * Builds units of a tracked item out of its components: the tracked parts
 * leave stock, the finished units arrive. A negative quantity takes units
 * apart again.
 */
class CreateItemAssembly extends Job implements HasOwner, HasSource, ShouldCreate
{
    protected $item;

    public function __construct(Item $item, $request)
    {
        $this->item = $item;

        parent::__construct($request);
    }

    public function handle(): ItemStockMovement
    {
        $this->authorize();

        \DB::transaction(function () {
            $quantity = (float) $this->request->get('quantity');
            $moved_at = CreateItemStockAdjustment::movedAt($this->request->get('moved_at'));

            $common = [
                'company_id' => $this->item->company_id,
                'type' => ItemStockMovement::ASSEMBLY,
                'moved_at' => $moved_at,
                'description' => $this->request->get('description'),
                'created_from' => $this->request->get('created_from'),
                'created_by' => $this->request->get('created_by'),
            ];

            foreach ($this->item->components()->with('component')->get() as $component) {
                // Untracked parts are not counted, the same as when a kit is sold
                if (! $component->component->track_stock) {
                    continue;
                }

                ItemStockMovement::create($common + [
                    'item_id' => $component->component_id,
                    'via_item_id' => $this->item->id,
                    'quantity' => -1 * $quantity * $component->quantity,
                ]);
            }

            $this->model = ItemStockMovement::create($common + [
                'item_id' => $this->item->id,
                'quantity' => $quantity,
            ]);
        });

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if (! $this->item->track_stock) {
            throw new \Exception(trans('stock.errors.not_tracked', ['item' => $this->item->name]));
        }

        if ($this->item->components()->doesntExist()) {
            throw new \Exception(trans('stock.errors.no_components', ['item' => $this->item->name]));
        }
    }
}
