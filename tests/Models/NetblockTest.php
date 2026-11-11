<?php

namespace tests\Models;

use AbuseIO\Models\Netblock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class NetblockTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testModelFactory()
    {
        $netblock = Netblock::factory()->create();
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
