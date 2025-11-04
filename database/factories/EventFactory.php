<?php

namespace Database\Factories;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $evidence = Evidence::factory()->create();

        $ticket = Ticket::query()->inRandomOrder()->first() ?? Ticket::factory()->create();

        return [
            'ticket_id'   => $ticket->id,
            'evidence_id' => $evidence->id,
            'source'      => $this->faker->name(),
            'timestamp'   => time(),
            'information' => json_encode([
                'engine' => $this->faker->sentence(5),
                'uri'    => $this->faker->url(),
            ]),
        ];
    }
}