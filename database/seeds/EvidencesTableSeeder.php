<?php

use Illuminate\Database\Seeder;

class EvidencesTableSeeder extends Seeder {

	public function run()
	{
        DB::table('evidences')->delete();

        $evidences = array(
            [
                'id'                        => '1',
                'filename'                  => '20150906/1_messageid',
                'sender'                    => '1 me',
                'subject'                   => 'i say 1',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '2',
                'filename'                  => '20150906/2_messageid',
                'sender'                    => '2 me',
                'subject'                   => 'i say 2',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '3',
                'filename'                  => '20150906/3_messageid',
                'sender'                    => '3 me',
                'subject'                   => 'i say 3',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '4',
                'filename'                  => '20150906/4_messageid',
                'sender'                    => '4 me',
                'subject'                   => 'i say 4',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ],
            [
                'id'                        => '5',
                'filename'                  => '20150906/5_messageid',
                'sender'                    => '5 me',
                'subject'                   => 'i say 5',
                'created_at'                => new DateTime,
                'updated_at'                => new DateTime
            ]
        );

        DB::table('evidences')->insert($evidences);
	}

}
