<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder {

	public function run()
	{
        DB::table('events')->delete();

        $events = array(
            [
                'id'                        => '1',
                'ticket_id'                 => '1',
                'evidence_id'               => '1',
                'source'                    => 'Simon Says',
                'timestamp'                 => new DateTime,
                'information'               => json_encode([
                    'engine' => 'infected website blob',
                    'uri' => '/dir1'
                ]),
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '2',
                'ticket_id'                 => '1',
                'evidence_id'               => '2',
                'source'                    => 'Simon Says',
                'timestamp'                 => new DateTime,
                'information'               => json_encode([
                    'engine' => 'infected website blob',
                    'uri' => '/dir2'
                ]),
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '3',
                'ticket_id'                 => '1',
                'evidence_id'               => '3',
                'source'                    => 'Simon Says',
                'timestamp'                 => new DateTime,
                'information'               => json_encode([
                    'engine' => 'infected website blob',
                    'uri' => '/dir3'
                ]),
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '4',
                'ticket_id'                 => '2',
                'evidence_id'               => '4',
                'source'                    => 'Simon Says',
                'timestamp'                 => new DateTime,
                'information'               => json_encode([
                    'engine' => 'infected botnet blob',
                    'cc_host' => 'x.x.x.x',
                    'cc_port' => '8080'
                ]),
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '5',
                'ticket_id'                 => '2',
                'evidence_id'               => '5',
                'source'                    => 'Simon Says',
                'timestamp'                 => new DateTime,
                'information'               => json_encode([
                    'engine' => 'infected botnet blob',
                    'cc_host' => 'x.x.x.x',
                    'cc_port' => '8080'
                ]),
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ]
        );

        DB::table('events')->insert($events);
	}

}
