<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\BankStatementImport as Model;
use App\Utilities\Date;

class BankStatementImport extends Factory
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
        $from = $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s');
        $to = Date::parse($from)->addDays($this->faker->numberBetween(1, 30))->format('Y-m-d H:i:s');

        return [
            'company_id'      => $this->company->id,
            'account_id'      => 1,
            'filename'        => $this->faker->word . '.xml',
            'statement_id'    => $this->faker->uuid,
            'iban'            => $this->faker->iban(),
            'currency_code'   => default_currency(),
            'opening_balance' => 0,
            'closing_balance' => $this->faker->randomFloat(2, 0, 10000),
            'statement_from'  => $from,
            'statement_to'    => $to,
            'total_lines'     => 0,
            'imported_lines'  => 0,
            'status'          => 'pending',
            'file_hash'       => $this->faker->sha256,
            'created_from'    => 'core::factory',
        ];
    }
}
