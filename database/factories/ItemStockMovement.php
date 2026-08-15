<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\Item;
use App\Models\Common\ItemStockMovement as Model;

class ItemStockMovement extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state: a manual adjustment of a tracked product.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => $this->company->id,
            'item_id' => fn () => Item::factory()->enabled()->create(['type' => 'product', 'track_stock' => true])->id,
            'type' => Model::ADJUSTMENT,
            'quantity' => $this->faker->randomFloat(2, 1, 20),
            'moved_at' => $this->faker->dateTimeBetween('-1 month'),
            'description' => $this->faker->sentence(3),
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

    public function quantity(float $quantity)
    {
        return $this->state([
            'quantity' => $quantity,
        ]);
    }
}
