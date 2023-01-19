<?php

namespace tests\Console\Commands\Role;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class AssignCommandTest.
 */
class AssignCommandTest extends TestCase
{
    public function testWithoutParams()
    {
        ob_start();
        $exitCode = Artisan::call('role:assign');
        $this->assertEquals(Command::FAILURE, $exitCode);

        $this->assertStringContainsString('Assign a role to a user', ob_get_clean());
    }
}
