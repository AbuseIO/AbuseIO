<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure Admin role exists with id 1 (kept by migration)
        // Add Abusedesk user role with id 2 expected by tests and seeders

        DB::table('roles')->where('id', '=', 2)->delete();

        DB::table('roles')->insert([
            [
                'id'          => 2,
                'name'        => 'Abusedesk',
                'description' => 'Abusedesk user',
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ],
        ]);
    }
}
