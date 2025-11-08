<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        // Preflight: verify migrations/tables exist before seeding
        $this->ensureDatabaseIsMigrated();

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

    /**
     * Ensure that core tables exist and migrations have run; otherwise abort seeding.
     */
    private function ensureDatabaseIsMigrated(): void
    {
        // Migrations table must exist and contain entries
        if (!Schema::hasTable('migrations')) {
            throw new \RuntimeException('Database not migrated: missing `migrations` table. Run `php artisan migrate` first.');
        }

        try {
            $migrationCount = DB::table('migrations')->count();
        } catch (\Throwable $e) {
            $migrationCount = 0;
        }

        if ($migrationCount === 0) {
            throw new \RuntimeException('Database not migrated: no migrations applied. Run `php artisan migrate` first.');
        }

        // Check presence of required tables used by seeders
        $requiredTables = [
            'brands',
            'accounts',
            'contacts',
            'domains',
            'netblocks',
            'tickets',
            'evidences',
            'events',
            'roles',
            'notes',
            'permissions',
            'permission_role',
            'role_user',
            'users',
        ];

        $missing = [];
        foreach ($requiredTables as $table) {
            if (!Schema::hasTable($table)) {
                $missing[] = $table;
            }
        }

        if (!empty($missing)) {
            throw new \RuntimeException(
                'Database not migrated: missing tables [' . implode(', ', $missing) . ']. Run `php artisan migrate` first.'
            );
        }
    }
}
