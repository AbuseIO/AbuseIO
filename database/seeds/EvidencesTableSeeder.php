<?php

use Illuminate\Database\Seeder;

class EvidencesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('evidences')->delete();

        $evidences = [
            [
                'id'         => '1',
                'filename'   => 'mailarchive/20150906/1_messageid',
                'sender'     => '1 me',
                'subject'    => 'i say 1',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ];

        DB::table('evidences')->insert($evidences);
    }
}
