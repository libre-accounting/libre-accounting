<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\BankStatementImport;
use App\Models\Banking\BankStatementLine as Model;

class BankStatementLine extends Factory
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
        $booked = $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s');
        $type = $this->faker->randomElement(['income', 'expense']);

        return [
            'company_id'               => $this->company->id,
            'bank_statement_import_id' => BankStatementImport::factory(),
            'account_id'               => 1,
            'transaction_id'           => null,
            'type'                     => $type,
            'booked_at'                => $booked,
            'valued_at'                => $booked,
            'amount'                   => $this->faker->randomFloat(2, 1, 5000),
            'currency_code'            => default_currency(),
            'bank_reference'           => $this->faker->bothify('REF-########'),
            'end_to_end_id'            => $this->faker->uuid,
            'counterparty_name'        => $this->faker->company,
            'counterparty_iban'        => $this->faker->iban(),
            'remittance_info'          => $this->faker->sentence,
            'description'              => $this->faker->sentence,
            'category_id'              => null,
            'contact_id'               => null,
            'document_id'              => null,
            'payment_method'           => 'offline-payments.cash.1',
            'status'                   => Model::STATUS_PENDING,
            'hash'                     => $this->faker->sha256,
            'created_from'             => 'core::factory',
        ];
    }

    public function income()
    {
        return $this->state(['type' => 'income']);
    }

    public function expense()
    {
        return $this->state(['type' => 'expense']);
    }
}
