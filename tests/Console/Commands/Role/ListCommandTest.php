<?php

namespace tests\Console\Commands\Role;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testAll()
    {
        $exitCode = Artisan::call(
            'role:list',
            [
                //
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('System Administrator', Artisan::output());
    }

    public function testWithValidFilter()
    {
        $exitCode = Artisan::call(
            'role:list',
            [
                '--filter' => 'Abuse',
            ]
        );

        $this->assertEquals($exitCode, 0);

        $output = Artisan::output();
        $this->assertStringContainsString('Abuse', $output);
        $this->assertStringNotContainsString('System Administrator', $output);
    }
}
