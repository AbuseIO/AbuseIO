<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use DatabaseTransactions;

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'role:show',
            [
                'role' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No matching role was found.', Artisan::output());
    }

    public function testWithValidNameFilter()
    {
        $exitCode = Artisan::call(
            'role:show',
            [
                'role' => 'Admin',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Admin', Artisan::output());
    }
}
