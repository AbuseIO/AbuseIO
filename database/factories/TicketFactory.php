<?php

namespace Database\Factories;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        $contactList = Contact::query()->get();

        $ipContact = $contactList->isEmpty() ? Contact::factory()->create() : $contactList->random();
        $domainContact = $contactList->isEmpty() ? Contact::factory()->create() : $contactList->random();

        $types = config('types.type');

        return [
            'ip'                            => $this->faker->boolean() ? $this->faker->ipv4() : $this->faker->ipv6(),
            'domain'                        => $this->faker->domainName(),
            'class_id'                      => array_rand(trans('classifications')),
            'type_id'                       => $types[array_rand($types)],
            'ip_contact_account_id'         => $ipContact->account_id,
            'ip_contact_reference'          => $ipContact->reference,
            'ip_contact_name'               => $ipContact->name,
            'ip_contact_email'              => $ipContact->email,
            'ip_contact_api_host'           => $ipContact->api_host,
            'ip_contact_auto_notify'        => $ipContact->auto_notify(),
            'ip_contact_notified_count'     => 0,
            'domain_contact_account_id'     => $domainContact->account_id,
            'domain_contact_reference'      => $domainContact->reference,
            'domain_contact_name'           => $domainContact->name,
            'domain_contact_email'          => $domainContact->email,
            'domain_contact_api_host'       => $domainContact->api_host,
            'domain_contact_auto_notify'    => $domainContact->auto_notify(),
            'domain_contact_notified_count' => 0,
            'status_id'                     => 'OPEN',
            'contact_status_id'             => 'OPEN',
            'last_notify_count'             => 1,
            'last_notify_timestamp'         => $this->faker->dateTime()->getTimestamp(),
        ];
    }
}