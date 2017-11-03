<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            [
                'id'          => 2,
                'name'        => 'Customer Internet',
                'description' => 'Customer internet department',
                'brand_id'    => 1,
                'token'       => generateApiToken(),
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ],
            [
                'id'          => 3,
                'name'        => 'Business Internet',
                'description' => 'Business internet department',
                'brand_id'    => 1,
                'token'       => generateApiToken(),
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ],
        ];

        DB::table('accounts')->insert($accounts);
    }
}
