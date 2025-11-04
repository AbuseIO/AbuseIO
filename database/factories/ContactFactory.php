<?php

namespace Database\Factories;

use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        $accountId = Account::query()->inRandomOrder()->value('id') ?? 1;

        return [
            'reference'  => sprintf('reference_%s', uniqid()),
            'name'       => $this->faker->name(),
            'email'      => $this->faker->safeEmail(),
            'api_host'   => $this->faker->url(),
            'enabled'    => $this->faker->boolean(),
            'account_id' => $accountId,
        ];
    }
}