<?php

namespace tests\Console\Commands\User;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testNetBlockListCommand()
    {
        $exitCode = Artisan::call(
            'user:list',
            [
                //
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('admin@isp.local', Artisan::output());
    }

    public function testNetBlockListCommandWithValidFilter()
    {
        $exitCode = Artisan::call(
            'user:list',
            [
                '--filter' => 'user',
            ]
        );

        $this->assertEquals($exitCode, 0);

        $output = Artisan::output();
        $this->assertContains('user@isp.local', $output);
        $this->assertNotContains('admin@account2.local', $output);
    }
}
