<?php

namespace Database\Seeders;

use AbuseIO\Models\Evidence;
use AbuseIO\Models\Event;
use AbuseIO\Models\Ticket;
use DateTime;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure there is at least one event per ticket.
        // Use a default demo evidence and avoid hardcoded IDs.
        $evidence = Evidence::query()->firstOrCreate(
            ['filename' => 'mailarchive/20150906/1_messageid'],
            [
                'sender'     => 'Seeder Demo',
                'subject'    => 'Demo evidence message',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ]
        );

        foreach (Ticket::all() as $ticket) {
            $hasEvent = Event::query()->where('ticket_id', $ticket->id)->exists();
            if (!$hasEvent) {
                Event::query()->firstOrCreate(
                    [
                        'ticket_id'   => $ticket->id,
                        'evidence_id' => $evidence->id,
                        'source'      => 'Seeder Default',
                    ],
                    [
                        'timestamp'   => time(),
                        'information' => json_encode([
                            'engine' => 'seeder',
                            'note'   => 'auto-generated minimal event',
                        ]),
                        'created_at'  => new DateTime(),
                        'updated_at'  => new DateTime(),
                    ]
                );
            }
        }
    }
}
