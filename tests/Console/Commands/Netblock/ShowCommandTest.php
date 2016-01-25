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
                '--filter' => 'Contact 6',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Contact 6', Artisan::output());
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
                '--filter' => '10.1.18.0',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Contact 8', Artisan::output());
    }

    public function testNetBlockShowWithStartEndFilter()
    {
        $exitCode = Artisan::call(
            'netblock:show',
            [
                '--filter' => '10.1.16.195',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Contact 6', Artisan::output());
    }
}
