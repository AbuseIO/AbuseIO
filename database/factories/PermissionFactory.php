<?php

namespace Database\Factories;

use AbuseIO\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->name(),
            'description' => $this->faker->sentence(6),
        ];
    }
}