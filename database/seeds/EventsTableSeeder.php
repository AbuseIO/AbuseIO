<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder {

	public function run()
	{
        DB::table('events')->delete();

        $events = array(
            [
                'id'                        => '1',
                'ticket_id'                 => '2',
                'evidence_id'               => '1',
                'source'                    => 'Simon Says',
                'uri'                       => '/dir',
                'timestamp'                 => new DateTime,
                'information'               => 'infected website blob',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '2',
                'ticket_id'                 => '2',
                'evidence_id'               => '2',
                'source'                    => 'Simon Says',
                'uri'                       => null,
                'timestamp'                 => new DateTime,
                'information'               => 'infected botnet blob',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ]
        );

        DB::table('events')->insert($events);
	}

}
