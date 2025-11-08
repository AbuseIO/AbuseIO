<?php

namespace Database\Seeders;

use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use DateTime;
use Illuminate\Database\Seeder;

class NotesTableSeeder extends Seeder
{
    public function run()
    {
        // Seed demo notes attached to real tickets, avoiding hardcoded IDs
        $demoNotes = [
            [
                'submitter' => 'Abusedesk',
                'text'      => 'Warned client that we will terminate service until resolved',
                'hidden'    => false,
            ],
            [
                'submitter' => 'IP Contact',
                'text'      => 'Please do not shut my internet off!',
                'hidden'    => false,
            ],
        ];

        foreach (Ticket::all() as $ticket) {
            foreach ($demoNotes as $noteData) {
                Note::query()->firstOrCreate(
                    [
                        'ticket_id' => $ticket->id,
                        'submitter' => $noteData['submitter'],
                        'text'      => $noteData['text'],
                    ],
                    [
                        'hidden'     => $noteData['hidden'],
                        'viewed'     => false,
                        'created_at' => new DateTime(),
                        'updated_at' => new DateTime(),
                    ]
                );
            }
        }
    }
}
