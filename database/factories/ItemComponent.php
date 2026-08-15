<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\Item;
use App\Models\Common\ItemComponent as Model;

class ItemComponent extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state: one unit of a new product goes into another new product.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => $this->company->id,
            'item_id' => fn () => Item::factory()->enabled()->create(['type' => 'product'])->id,
            'component_id' => fn () => Item::factory()->enabled()->create(['type' => 'product'])->id,
            'quantity' => 1,
            'created_from' => 'core::factory',
        ];
    }

    public function forItem(Item $item)
    {
        return $this->state([
            'company_id' => $item->company_id,
            'item_id' => $item->id,
        ]);
    }

    public function component(Item $component)
    {
        return $this->state([
            'component_id' => $component->id,
        ]);
    }

    public function quantity(float $quantity)
    {
        return $this->state([
            'quantity' => $quantity,
        ]);
    }
}
