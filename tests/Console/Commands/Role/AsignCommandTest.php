<?php

namespace tests\Console\Commands\Role;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class AssignCommandTest.
 */
class AsignCommandTest extends TestCase
{
    public function testWithoutParams()
    {
        $exitCode = Artisan::call('role:assign');
        $this->assertEquals($exitCode, 1);
        $this->assertStringContainsString('Assign a role to a user', Artisan::output());
    }
}
