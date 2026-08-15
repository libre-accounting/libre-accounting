<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Item;
use App\Models\Common\ItemComponent;
use App\Utilities\Stock;

/**
 * Adds a part to an item's recipe, or changes how much of it goes in.
 */
class CreateItemComponent extends Job implements HasOwner, HasSource, ShouldCreate
{
    protected $item;

    protected $component;

    public function __construct(Item $item, $request)
    {
        $this->item = $item;

        parent::__construct($request);

        $this->component = Item::find($this->request->get('component_id'));
    }

    public function handle(): ItemComponent
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->model = ItemComponent::firstOrNew([
                'item_id' => $this->item->id,
                'component_id' => $this->component->id,
            ]);

            $this->model->fill([
                'company_id' => $this->item->company_id,
                'quantity' => $this->request->get('quantity'),
                'created_from' => $this->model->created_from ?? $this->request->get('created_from'),
                'created_by' => $this->model->created_by ?? $this->request->get('created_by'),
            ])->save();

            // Documents that sold this item as a kit now consume the new recipe
            Stock::resyncItem($this->item);
        });

        return $this->model;
    }

    /**
     * Determine if this action is applicable: recipes are one level deep.
     */
    public function authorize(): void
    {
        if (! $this->component) {
            throw new \Exception(trans('validation.exists', ['attribute' => 'component_id']));
        }

        if ($this->component->id == $this->item->id) {
            throw new \Exception(trans('stock.errors.component_is_self'));
        }

        if ($this->component->components()->exists()) {
            throw new \Exception(trans('stock.errors.component_is_kit', ['item' => $this->component->name]));
        }

        if ($this->item->component_of()->exists()) {
            throw new \Exception(trans('stock.errors.kit_as_component', ['item' => $this->item->name]));
        }
    }
}
