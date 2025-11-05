<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('events')->delete();

        $events = [
            [
                'id'          => '1',
                'ticket_id'   => '1',
                'evidence_id' => '1',
                'source'      => 'Simon Says',
                'timestamp'   => time(),
                'information' => json_encode(
                    [
                        'engine' => 'infected website blob',
                        'uri'    => '/dir1',
                    ]
                ),
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ];

        DB::table('events')->insert($events);
    }
}
