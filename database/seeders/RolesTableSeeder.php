<?php

namespace Database\Seeders;

use AbuseIO\Models\Role;
use DateTime;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure Abusedesk role exists; do not force a specific ID
        Role::query()->firstOrCreate(
            ['name' => 'Abusedesk'],
            [
                'description' => 'Abusedesk user',
                'created_at'  => new DateTime(),
                'updated_at'  => new DateTime(),
            ]
        );
    }
}
