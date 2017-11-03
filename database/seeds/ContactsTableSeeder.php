<?php

use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('contacts')->delete();

        $contacts = [
            [
                'id'         => 1,
                'reference'  => 'JOHND',
                'name'       => 'John Doe',
                'email'      => 'j.doe@customers.isp.local',
                'api_host'   => null,
                'enabled'    => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'account_id' => 1,
            ],
            [
                'id'         => 2,
                'reference'  => 'CUST1',
                'name'       => 'Customer 1',
                'email'      => 'cust1@local.lan',
                'api_host'   => null,
                'enabled'    => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'account_id' => 2,
            ],
            [
                'id'         => 3,
                'reference'  => 'ISP1',
                'name'       => 'ISP Business Internet',
                'email'      => 'abuse@business.isp.local',
                'api_host'   => null,
                'enabled'    => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                'account_id' => 3,
            ],
        ];

        DB::table('contacts')->insert($contacts);
    }
}
