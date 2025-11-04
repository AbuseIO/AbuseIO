<?php

namespace Database\Factories;

use AbuseIO\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->name(),
            'description' => $this->faker->sentence(),
        ];
    }
}