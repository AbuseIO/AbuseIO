<?php

use Illuminate\Database\Seeder;

class NotesTableSeeder extends Seeder {

	public function run()
	{
        DB::table('notes')->delete();

        $notes = array(
            [
                'id'                        => '1',
                'ticket_id'                 => '1',
                'submitter'                 => 'AbuseDesk',
                'text'                      => 'Warned client that we will terminate service until resolved',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '2',
                'ticket_id'                 => '1',
                'submitter'                 => 'Contact',
                'text'                      => 'Oh please dont shut my internet off!',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '3',
                'ticket_id'                 => '2',
                'submitter'                 => 'AbuseDesk',
                'text'                      => 'Placed in quarantine until client is contacted',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '4',
                'ticket_id'                 => '2',
                'submitter'                 => 'Contact',
                'text'                      => 'Antivirus has removed malware',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ]
        );

        DB::table('notes')->insert($notes);
	}

}
