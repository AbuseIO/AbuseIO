<?php

namespace Database\Factories;

use AbuseIO\Models\TicketGraphPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketGraphPointFactory extends Factory
{
    protected $model = TicketGraphPoint::class;

    public function definition()
    {
        return [
            'day_date'  => $this->faker->date('Y-m-d'),
            'class'     => $this->faker->randomElement(['demo', 'red', 'blue']),
            'type'      => $this->faker->randomElement(['demo', 'info', 'warn']),
            'status'    => $this->faker->randomElement(['demo', 'OPEN', 'CLOSED']),
            'count'     => $this->faker->numberBetween(1, 100),
            'lifecycle' => $this->faker->randomElement(['created_at', 'updated_at']),
        ];
    }
}