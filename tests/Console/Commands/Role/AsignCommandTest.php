<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class AssignCommandTest.
 */
class AssignCommandTest extends TestCase
{


    public function testWithoutParams()
    {
        ob_start();
        $exitCode = Artisan::call('role:assign');
        $this->assertEquals($exitCode, 0);

        $this->assertContains('Assign a role to a user', ob_get_clean());
    }
}
