<?php

namespace Database\Seeders;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use DateTime;
use Illuminate\Database\Seeder;

/**
 * Class NetblocksTableSeeder.
 */
class NetblocksTableSeeder extends Seeder
{
    public function run()
    {
        $john = Contact::query()->where('reference', 'JOHND')->first();
        $cust1 = Contact::query()->where('reference', 'CUST1')->first();
        $isp1 = Contact::query()->where('reference', 'ISP1')->first();

        $netblocks = [
            [
                'first_ip'    => '172.16.10.13',
                'last_ip'     => '172.16.10.13',
                'description' => "Dedicated IP address for John's server",
                'contact_id'  => optional($john)->id,
            ],
            [
                'first_ip'    => '10.0.2.0',
                'last_ip'     => '10.0.2.255',
                'description' => 'Netblock for customer 1',
                'contact_id'  => optional($cust1)->id,
            ],
            [
                'first_ip'    => '192.168.1.0',
                'last_ip'     => '192.168.3.255',
                'description' => 'Netblock for ISP1',
                'contact_id'  => optional($isp1)->id,
            ],
            [
                'first_ip'    => 'fdf1:cb9d:f59e:19b0:0:0:0:0',
                'last_ip'     => 'fdf1:cb9d:f59e:19b0:ffff:ffff:ffff:ffff',
                'description' => 'IPv6 Netblock for ISP1',
                'contact_id'  => optional($isp1)->id,
            ],
            [
                'first_ip'    => '10.17.18.0',
                'last_ip'     => '10.17.18.255',
                'description' => 'Netblock for ISP1',
                'contact_id'  => optional($isp1)->id,
            ],
            [
                'first_ip'    => '0.0.0.0',
                'last_ip'     => '255.255.255.255',
                'description' => 'Fallback netblock for demo purposes',
                'contact_id'  => optional($isp1)->id,
            ],
        ];

        foreach ($netblocks as $row) {
            Netblock::query()->updateOrCreate(
                ['first_ip' => $row['first_ip'], 'last_ip' => $row['last_ip']],
                [
                    'description' => $row['description'],
                    'contact_id'  => $row['contact_id'],
                    'enabled'     => 1,
                    'created_at'  => new DateTime(),
                    'updated_at'  => new DateTime(),
                ]
            );
        }
    }
}
