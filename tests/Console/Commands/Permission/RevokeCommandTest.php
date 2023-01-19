<?php

namespace tests\Console\Commands\Permission;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class RevokeCommandTest extends TestCase
{
    public function testWithoutArguments()
    {
        ob_start();
        $exitCode = Artisan::call('permission:revoke');
        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Revokes a permission from a role', ob_get_clean());
    }
}
