<?php

namespace tests\Console\Commands\Netblock;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testNetBlockListCommand()
    {
        $exitCode = Artisan::call('netblock:list', []);

        $this->assertEquals($exitCode, 0);
        $this->assertContains('John Doe', Artisan::output());
    }

    public function testNetBlockListCommandWithValidFilter()
    {
        $exitCode = Artisan::call(
            'netblock:list',
            [
                '--filter' => '192.168.1.0',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('ISP Business Internet', Artisan::output());
        $this->assertNotContains('Customer 1', Artisan::output());
    }
}
