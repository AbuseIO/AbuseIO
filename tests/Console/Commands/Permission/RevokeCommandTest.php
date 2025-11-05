<?php

namespace tests\Console\Commands\Permission;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class RevokeCommandTest extends TestCase
{
    public function testWithoutArguments()
    {
        $exitCode = Artisan::call('permission:revoke');
        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Revokes a permission from a role', Artisan::output());
    }
}
