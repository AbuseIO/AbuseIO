<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Seed core tables in a deterministic order
        $this->call([
            AccountsTableSeeder::class,
            ContactsTableSeeder::class,
            DomainsTableSeeder::class,
            NetblocksTableSeeder::class,
            TicketsTableSeeder::class,
            EvidencesTableSeeder::class,
            EventsTableSeeder::class,
            RolesTableSeeder::class,
            NotesTableSeeder::class,
            RolePermissionSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
