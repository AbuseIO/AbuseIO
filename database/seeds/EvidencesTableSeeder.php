<?php

use Illuminate\Database\Seeder;

class EvidencesTableSeeder extends Seeder {

	public function run()
	{
        DB::table('evidences')->delete();

        $evidences = array(
            [
                'id'                        => '1',
                'data'                      => 'its true when 1 says it is!',
                'sender'                    => '1 me',
                'subject'                   => 'i say 1',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '2',
                'data'                      => 'its true when 2 says it is!',
                'sender'                    => '2 me',
                'subject'                   => 'i say 2',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '3',
                'data'                      => 'its true when 3 says it is!',
                'sender'                    => '3 me',
                'subject'                   => 'i say 3',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '4',
                'data'                      => 'its true when 4 says it is!',
                'sender'                    => '4 me',
                'subject'                   => 'i say 4',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '5',
                'data'                      => 'its true when 5 says it is!',
                'sender'                    => '5 me',
                'subject'                   => 'i say 5',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ]
        );

        DB::table('evidences')->insert($evidences);
	}

}
