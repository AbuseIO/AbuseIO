<?php

use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('domains')->delete();

        $domains = array(
            [
                'id'            => 1,
                'name'          => 'domain1.com',
                'contact_id'    => 11,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 2,
                'name'          => 'domain2.com',
                'contact_id'    => 12,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 3,
                'name'          => 'domain3.com',
                'contact_id'    => 13,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 4,
                'name'          => 'domain4.com',
                'contact_id'    => 14,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 5,
                'name'          => 'domain5.com',
                'contact_id'    => 15,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 6,
                'name'          => 'domain6.com',
                'contact_id'    => 16,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 7,
                'name'          => 'domain7.com',
                'contact_id'    => 17,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 8,
                'name'          => 'domain8.com',
                'contact_id'    => 18,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 9,
                'name'          => 'domain9.com',
                'contact_id'    => 19,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 10,
                'name'          => 'domain10.com',
                'contact_id'    => 19,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 11,
                'name'          => 'domain11.com',
                'contact_id'    => 11,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 12,
                'name'          => 'domain12.com',
                'contact_id'    => 12,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 13,
                'name'          => 'domain13.com',
                'contact_id'    => 13,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 14,
                'name'          => 'domain14.com',
                'contact_id'    => 14,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ],
            [
                'id'            => 15,
                'name'          => 'domain15.com',
                'contact_id'    => 15,
                'enabled'       => 1,
                'created_at'    => new DateTime,
                'updated_at'    => new DateTime,
            ]
        );

        DB::table('domains')->insert($domains);
    }

}




