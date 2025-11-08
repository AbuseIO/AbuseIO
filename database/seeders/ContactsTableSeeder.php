<?php

namespace Database\Seeders;

use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use DateTime;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    public function run()
    {
        // Determine accounts dynamically to avoid hardcoded IDs
        $defaultAccount = Account::query()->orderBy('id')->first();
        $customerAccount = Account::query()->where('name', 'Customer Internet')->first() ?? $defaultAccount;
        $businessAccount = Account::query()->where('name', 'Business Internet')->first() ?? $defaultAccount;

        $contacts = [
            [
                'reference'  => 'JOHND',
                'name'       => 'John Doe',
                'email'      => 'j.doe@customers.isp.local',
                'api_host'   => null,
                'enabled'    => 1,
                'account_id' => optional($defaultAccount)->id,
            ],
            [
                'reference'  => 'CUST1',
                'name'       => 'Customer 1',
                'email'      => 'cust1@local.lan',
                'api_host'   => null,
                'enabled'    => 1,
                'account_id' => optional($customerAccount)->id,
            ],
            [
                'reference'  => 'ISP1',
                'name'       => 'ISP Business Internet',
                'email'      => 'abuse@business.isp.local',
                'api_host'   => null,
                'enabled'    => 1,
                'account_id' => optional($businessAccount)->id,
            ],
        ];

        foreach ($contacts as $row) {
            Contact::query()->updateOrCreate(
                ['reference' => $row['reference']],
                [
                    'name'       => $row['name'],
                    'email'      => $row['email'],
                    'api_host'   => $row['api_host'],
                    'enabled'    => $row['enabled'],
                    'account_id' => $row['account_id'],
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                ]
            );
        }
    }
}
