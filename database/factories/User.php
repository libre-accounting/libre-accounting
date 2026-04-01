<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Auth\User as Model;
use Illuminate\Support\Str;

class User extends Factory
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
        $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

        // Resolve real ids rather than hardcoding 1. Under PostgreSQL the id
        // sequence is not reset by RefreshDatabase's per-test rollback, so the
        // admin role / seeded company may not be id 1 — hardcoding it caused
        // user_roles foreign-key violations. Guard the lookup so the factory
        // still works before the schema exists (e.g. guest auth flows).
        $role_id = \Illuminate\Support\Facades\Schema::hasTable('roles')
            ? (\App\Models\Auth\Role::where('name', 'admin')->value('id') ?? 1)
            : 1;
        $company_id = company_id() ?: 1;

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->freeEmail,
            'password' => $password,
            'password_confirmation' => $password,
            'remember_token' => Str::random(10),
            'locale' => 'en-GB',
            'companies' => [(string) $company_id],
            'roles' => (string) $role_id,
            'enabled' => $this->faker->boolean ? 1 : 0,
            'landing_page' => 'dashboard',
            'created_from' => 'core::factory',
        ];
    }

    /**
     * Indicate that the model is enabled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function enabled()
    {
        return $this->state([
            'enabled' => 1,
        ]);
    }

    /**
     * Indicate that the model is disabled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function disabled()
    {
        return $this->state([
            'enabled' => 0,
        ]);
    }
}
