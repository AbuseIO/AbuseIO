<?php

namespace Database\Factories;

use AbuseIO\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'email'      => $this->faker->safeEmail(),
            'password'   => $this->faker->password(6),
            'account_id' => 1,
            'locale'     => 'en',
            'disabled'   => $this->faker->boolean(),
        ];
    }
}
