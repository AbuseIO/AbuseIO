<?php

use Illuminate\Database\Seeder;

class NotesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('notes')->delete();

        $notes = [
            [
                'id'         => '1',
                'ticket_id'  => '1',
                'submitter'  => 'Abusedesk',
                'text'       => 'Warned client that we will terminate service until resolved',
                'hidden'     => false,
                'viewed'     => false,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => '2',
                'ticket_id'  => '1',
                'submitter'  => 'IP Contact',
                'text'       => 'Oh please dont shut my internet off!',
                'hidden'     => false,
                'viewed'     => false,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => '3',
                'ticket_id'  => '1',
                'submitter'  => 'Abusedesk',
                'text'       => 'Well too bad!',
                'hidden'     => true,
                'viewed'     => false,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => '4',
                'ticket_id'  => '1',
                'submitter'  => 'Domain Contact',
                'text'       => 'Hoster ... Please fix the problem ...',
                'hidden'     => false,
                'viewed'     => false,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => '5',
                'ticket_id'  => '2',
                'submitter'  => 'Abusedesk (Default Admin)',
                'text'       => 'Placed in quarantine until client is contacted',
                'hidden'     => true,
                'viewed'     => false,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'id'         => '6',
                'ticket_id'  => '2',
                'submitter'  => 'IP Contact',
                'text'       => 'Antivirus has removed malware',
                'hidden'     => false,
                'viewed'     => false,
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ];

        DB::table('notes')->insert($notes);
    }
}
