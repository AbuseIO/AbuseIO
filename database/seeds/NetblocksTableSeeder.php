<?php

use Illuminate\Database\Seeder;
use AbuseIO\ICF;

class NetblocksTableSeeder extends Seeder
{

    public function run()
    {

        DB::table('netblocks')->delete();

        $netblocks = [
            [
                'id' => 1,
                'first_ip' => '172.16.10.13',
                'last_ip' => '172.16.10.13',
                'first_ip_int' => ICF::InetPtoi('172.16.10.13'),
                'last_ip_int' => ICF::InetPtoi('172.16.10.13'),
                'description' => "Dedicated IP address for John's server",
                'contact_id' => 1,
                'enabled' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
            [
                'id' => 2,
                'first_ip' => '10.0.2.0',
                'last_ip' => '10.0.2.255',
                'first_ip_int' => ICF::InetPtoi('10.0.2.0'),
                'last_ip_int' => ICF::InetPtoi('10.0.2.255'),
                'description' => 'Netblock for customer 1',
                'contact_id' => 2,
                'enabled' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
            [
                'id' => 3,
                'first_ip' => '192.168.1.0',
                'last_ip' => '192.168.3.255',
                'first_ip_int' => ICF::InetPtoi('10.0.3.0'),
                'last_ip_int' => ICF::InetPtoi('192.168.3.255'),
                'description' => 'Netblock for ISP1',
                'contact_id' => 3,
                'enabled' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
            [
                'id' => 4,
                'first_ip' => 'fdf1:cb9d:f59e:19b0:0:0:0:0',
                'last_ip' => 'fdf1:cb9d:f59e:19b0:ffff:ffff:ffff:ffff',
                'first_ip_int' => ICF::InetPtoi('fdf1:cb9d:f59e:19b0:0:0:0:0'),
                'last_ip_int' => ICF::InetPtoi('fdf1:cb9d:f59e:19b0:ffff:ffff:ffff:ffff'),
                'description' => 'IPv6 Netblock for ISP1',
                'contact_id' => 3,
                'enabled' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ],
        ];

        DB::table('netblocks')->insert($netblocks);
    }
}
