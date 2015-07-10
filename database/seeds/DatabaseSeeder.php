<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        Model::unguard();

        $this->call('ContactsTableSeeder');
        $this->call('NetblocksTableSeeder');
        $this->call('DomainsTableSeeder');

        $this->call('EvidencesTableSeeder');
        $this->call('EventsTableSeeder');
        $this->call('TicketsTableSeeder');
        $this->call('NotesTableSeeder');

    }
}
