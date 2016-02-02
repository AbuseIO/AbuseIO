<?php

namespace tests\Console\Commands\Netblock;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    public function testWithValidContactFilter()
    {
        $exitCode = Artisan::call(
            'netblock:show',
            [
                '--filter' => 'Customer 1',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Customer 1', Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'netblock:show',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No matching netblocks where found.', Artisan::output());
    }

    public function testWithStartIpFilter()
    {
        $exitCode = Artisan::call(
            'netblock:show',
            [
                '--filter' => '172.16.10.13',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('John Doe', Artisan::output());
    }

    public function testNetBlockShowWithStartEndFilter()
    {
        $exitCode = Artisan::call(
            'netblock:show',
            [
                '--filter' => 'fdf1:cb9d:f59e:19b0:ffff:ffff:ffff:ffff',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('ISP Business Internet', Artisan::output());
    }
}
