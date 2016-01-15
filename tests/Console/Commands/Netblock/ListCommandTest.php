<?php

namespace tests\Console\Commands\Netblock;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testNetBlockListCommand()
    {
        $exitCode = Artisan::call('netblock:list', []);

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Global internet', Artisan::output());
    }

    public function testNetBlockListCommandWithValidFilter()
    {
        $exitCode = Artisan::call(
            'netblock:list',
            [
                '--filter' => '10.1.16.128',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Customer 6', Artisan::output());
        $this->assertNotContains('Global internet', Artisan::output());
    }
}
