<?php

namespace Database\Seeders;

use AbuseIO\Models\Evidence;
use DateTime;
use Illuminate\Database\Seeder;

class EvidencesTableSeeder extends Seeder
{
    public function run()
    {
        // Seed a default demo evidence if not present; avoid hardcoded IDs
        Evidence::query()->updateOrCreate(
            ['filename' => 'mailarchive/20150906/1_messageid'],
            [
                'sender'     => 'Seeder Demo',
                'subject'    => 'Demo evidence message',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ]
        );
    }
}
