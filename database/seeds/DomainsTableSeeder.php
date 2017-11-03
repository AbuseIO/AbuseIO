<?php

use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('domains')->delete();

        $domains = [
            [
                'id'         => 1,
                'name'       => 'john-doe.tld',
                'contact_id' => 1,
                'enabled'    => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => 2,
                'name'       => 'johndoe.tld',
                'contact_id' => 1,
                'enabled'    => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => 3,
                'name'       => 'customer1.tld',
                'contact_id' => 2,
                'enabled'    => 1,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ];

        DB::table('domains')->insert($domains);
    }
}
