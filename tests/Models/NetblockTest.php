<?php

namespace tests\Models;

use AbuseIO\Models\Netblock;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class NetblockTest extends TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        $netblock = factory(Netblock::class)->create();
        $netblockFromDB = Netblock::where(
            [
                'first_ip' => $netblock->first_ip,
                'last_ip'  => $netblock->last_ip,
            ]
        )->first();

        $this->assertEquals($netblock->first_ip, $netblockFromDB->first_ip);
        $this->assertEquals($netblock->last_ip, $netblockFromDB->last_ip);
    }
}
