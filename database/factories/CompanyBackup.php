<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\CompanyBackup as Model;

class CompanyBackup extends Factory
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
        return [
            'company_id'   => $this->company->id,
            'user_id'      => optional($this->user)->id ?? 1,
            'type'         => Model::TYPE_EXPORT,
            'status'       => Model::STATUS_PENDING,
            'total'        => 0,
            'processed'    => 0,
            'created_from' => 'core::factory',
        ];
    }

    public function export(): static
    {
        return $this->state(['type' => Model::TYPE_EXPORT]);
    }

    public function import(): static
    {
        return $this->state(['type' => Model::TYPE_IMPORT]);
    }

    public function completed(): static
    {
        return $this->state(['status' => Model::STATUS_COMPLETED]);
    }
}
