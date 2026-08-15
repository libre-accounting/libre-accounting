<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Item;
use App\Models\Common\ItemStockMovement;
use App\Utilities\Date;
use App\Utilities\Stock;

/**
 * Records a manual stock movement for a tracked item.
 *
 * The request carries either `quantity` (set the stock on hand to this) or
 * `change` (move the stock by this much), plus optional `moved_at` and
 * `description`.
 */
class CreateItemStockAdjustment extends Job implements HasOwner, HasSource, ShouldCreate
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
            $change = $this->request->filled('change')
                ? (float) $this->request->get('change')
                : (float) $this->request->get('quantity') - Stock::onHand($this->item->id);

            $this->model = ItemStockMovement::create([
                'company_id' => $this->item->company_id,
                'item_id' => $this->item->id,
                'type' => ItemStockMovement::ADJUSTMENT,
                'quantity' => $change,
                'moved_at' => static::movedAt($this->request->get('moved_at')),
                'description' => $this->request->get('description'),
                'created_from' => $this->request->get('created_from'),
                'created_by' => $this->request->get('created_by'),
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
    }

    /**
     * A bare date gets the current time, so movements of one day keep their order.
     */
    public static function movedAt($value): Date
    {
        if (empty($value)) {
            return Date::now();
        }

        $date = Date::parse($value);

        if ($date->format('H:i:s') == '00:00:00') {
            $date->setTimeFrom(Date::now());
        }

        return $date;
    }
}
