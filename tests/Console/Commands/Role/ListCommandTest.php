<?php

namespace tests\Console\Commands\Role;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

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

        $this->assertEquals(Command::SUCCESS, $exitCode);
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

        $this->assertEquals(Command::SUCCESS, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Abuse', $output);
        $this->assertStringNotContainsString('System Administrator', $output);
    }
}
