<?php

use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('contacts')->delete();

        $contacts = [
            [
                'id'            => 1,
                'reference'     => 'INTERNET',
                'name'          => 'Global internet',
                'email'         => 'internet@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 2,
                'reference'     => 'CERT',
                'name'          => 'A country CERT',
                'email'         => 'cert@local.lan',
                'api_host'      => 'https://abuseio-api.cert.local.lan',
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 3,
                'reference'     => 'ISP',
                'name'          => 'Internet Service Provider',
                'email'         => '',
                'api_host'      => 'https://abuseio-api.isp.local.lan',
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 11,
                'reference'     => 'CONT1',
                'name'          => 'Contact 1',
                'email'         => 'cont1@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 12,
                'reference'     => 'CONT2',
                'name'          => 'Contact 2',
                'email'         => 'cont2@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 13,
                'reference'     => 'CONT3',
                'name'          => 'Contact 3',
                'email'         => 'cont3@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 14,
                'reference'     => 'CONT4',
                'name'          => 'Contact 4',
                'email'         => 'cont4@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 15,
                'reference'     => 'CONT5',
                'name'          => 'Contact 5',
                'email'         => 'cont5@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 16,
                'reference'     => 'CONT6',
                'name'          => 'Contact 6',
                'email'         => 'cont6@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 17,
                'reference'     => 'CONT7',
                'name'          => 'Contact 7',
                'email'         => 'cont7@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 18,
                'reference'     => 'CONT8',
                'name'          => 'Contact 8',
                'email'         => 'cont8@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ],
            [
                'id'            => 19,
                'reference'     => 'CONT9',
                'name'          => 'Contact 9',
                'email'         => 'cont9@local.lan',
                'api_host'      => null,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
                'account_id'    => 1,
            ]
        ];

        DB::table('contacts')->insert($contacts);
    }
}
