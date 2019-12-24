<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    use DatabaseTransactions;

    private $role;

    private function initDB()
    {
        $this->role = factory(Role::class)->create();
    }

    public function testValid()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'role:delete',
            [
                'role' => $this->role->id,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('role has been deleted', Artisan::output());
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'role:delete',
            [
                'role' => '100000',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find role', Artisan::output());
    }
}
