<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\Reconciliation as Model;
use App\Utilities\Date;

class Reconciliation extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $started_at = $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s');
        $ended_at = Date::parse($started_at)->addDays($this->faker->randomNumber(3))->format('Y-m-d H:i:s');

        return [
            'company_id' => $this->company->id,
            // Use the seeded default account rather than a hardcoded id 1. Under
            // PostgreSQL the id sequence is not reset by RefreshDatabase, so the
            // seeded bank account may not be id 1, which left account->currency
            // null and broke the reconciliation edit view.
            'account_id' => setting('default.account'),
            'closing_balance' => '10',
            'started_at' => $started_at,
            'ended_at' => $ended_at,
            'reconciled' => $this->faker->boolean,
            'created_from' => 'core::factory',
        ];
    }

    /**
     * Indicate that the model is reconciled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function reconciled()
    {
        return $this->state([
            'reconciled' => true,
        ]);
    }

    /**
     * Indicate that the model is not reconciled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function notreconciled()
    {
        return $this->state([
            'reconciled' => false,
        ]);
    }
}
