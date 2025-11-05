<?php

namespace Database\Factories;

use AbuseIO\Models\Evidence;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvidenceFactory extends Factory
{
    protected $model = Evidence::class;

    public function definition()
    {
        $today = date('Ymd');

        return [
            'filename' => sprintf('mailarchive/%s/%s_messageid', $today, uniqid()),
            'sender'   => $this->faker->name(),
            'subject'  => $this->faker->sentence(),
        ];
    }
}
