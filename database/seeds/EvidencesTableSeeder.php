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
            ]
        );

        DB::table('evidences')->insert($evidences);
	}

}
