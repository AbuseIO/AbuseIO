<?php

namespace tests\Console\Commands\Permission;

use AbuseIO\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
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
        $this->assertEquals(0, $exitCode);
        $this->assertContains('Revokes a permission from a role', ob_get_clean());
    }

}
