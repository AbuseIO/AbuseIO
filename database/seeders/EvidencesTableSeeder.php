<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
