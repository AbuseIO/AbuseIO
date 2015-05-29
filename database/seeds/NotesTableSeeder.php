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
                'submitter'                 => 'abusedesk',
                'text'                      => 'Warned client that we will terminate service until resolved',
                'hidden'                    => false,
                'viewed'                    => false,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '2',
                'ticket_id'                 => '1',
                'submitter'                 => 'contact',
                'text'                      => 'Oh please dont shut my internet off!',
                'hidden'                    => false,
                'viewed'                    => false,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '3',
                'ticket_id'                 => '1',
                'submitter'                 => 'abusedesk',
                'text'                      => 'to bad then!',
                'hidden'                    => true,
                'viewed'                    => false,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '4',
                'ticket_id'                 => '2',
                'submitter'                 => 'abusedesk',
                'text'                      => 'Placed in quarantine until client is contacted',
                'hidden'                    => true,
                'viewed'                    => false,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '5',
                'ticket_id'                 => '2',
                'submitter'                 => 'contact',
                'text'                      => 'Antivirus has removed malware',
                'hidden'                    => false,
                'viewed'                    => false,
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ]
        );

        DB::table('notes')->insert($notes);
	}

}
