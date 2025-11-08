<?php

namespace Database\Seeders;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use DateTime;
use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder
{
    public function run()
    {
        // Resolve contacts dynamically and create domains linked to them
        $john = Contact::query()->where('reference', 'JOHND')->first();
        $cust1 = Contact::query()->where('reference', 'CUST1')->first();

        $domains = [
            [
                'name'       => 'john-doe.tld',
                'contact_id' => optional($john)->id,
            ],
            [
                'name'       => 'johndoe.tld',
                'contact_id' => optional($john)->id,
            ],
            [
                'name'       => 'customer1.tld',
                'contact_id' => optional($cust1)->id,
            ],
        ];

        foreach ($domains as $row) {
            Domain::query()->updateOrCreate(
                ['name' => $row['name']],
                [
                    'contact_id' => $row['contact_id'],
                    'enabled'    => 1,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                ]
            );
        }
    }
}
