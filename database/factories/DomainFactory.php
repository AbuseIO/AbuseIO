<?php

namespace Database\Factories;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition()
    {
        $contactId = Contact::query()->inRandomOrder()->value('id') ?? 1;

        return [
            'name'       => uniqid().$this->faker->domainName(),
            'contact_id' => $contactId,
            'enabled'    => $this->faker->boolean(),
        ];
    }
}