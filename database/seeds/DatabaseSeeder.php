<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        factory(AbuseIO\Models\Contact::class, 5)->create(
            [
                'auto_notify' => 0
            ]
        );
        factory(AbuseIO\Models\Netblock::class, 10)->create();
        factory(AbuseIO\Models\Domain::class, 10)->create();
        factory(AbuseIO\Models\Account::class, 4)->create();
        factory(AbuseIO\Models\User::class, 4)->create();
        factory(AbuseIO\Models\Ticket::class, 10)->create();

        factory(AbuseIO\Models\Role::class)->create(
            [
                'name'          => 'Abuse',
                'description'   => 'Abusedesk User',
            ]
        );

        // Seed the permissions and roles AbuseIO uses.
        $this->call('RolePermissionSeeder');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
