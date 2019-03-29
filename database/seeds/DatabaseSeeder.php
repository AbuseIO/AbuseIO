<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        factory(AbuseIO\Models\Contact::class, 5)->create();
        factory(AbuseIO\Models\Netblock::class, 10)->create();
        factory(AbuseIO\Models\Domain::class, 10)->create();
        factory(AbuseIO\Models\Account::class, 4)->create();
        factory(AbuseIO\Models\User::class, 4)->create();
        factory(AbuseIO\Models\Ticket::class, 10)->create();

        // give the tickets some events
        \AbuseIO\Models\Ticket::all()->each(function ($ticket) {
            $events = random_int(1, 24);
            factory(AbuseIO\Models\Event::class, $events)->create(['ticket_id' => $ticket->id]);
        });
        // give the tickets some notes
        \AbuseIO\Models\Ticket::all()->each(function ($ticket) {
            $notes = random_int(1, 24);
            factory(AbuseIO\Models\Note::class, $notes)->create(['ticket_id' => $ticket->id]);
        });

        factory(AbuseIO\Models\Role::class)->create(
            [
                'name'        => 'Abuse',
                'description' => 'Abusedesk User',
            ]
        );

        // Seed the permissions and roles AbuseIO uses.
        $this->call('RolePermissionSeeder');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
