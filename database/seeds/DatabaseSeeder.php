<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $this->call('ContactsTableSeeder');
        $this->call('NetblocksTableSeeder');
        $this->call('DomainsTableSeeder');

//        $this->call('EvidencesTableSeeder');
//        $this->call('EventsTableSeeder');
//        $this->call('TicketsTableSeeder');
//        $this->call('NotesTableSeeder');

        $this->call('AccountsTableSeeder');
        $this->call('UsersTableSeeder');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
