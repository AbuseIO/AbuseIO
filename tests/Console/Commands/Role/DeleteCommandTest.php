<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

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

        $this->assertEquals(Command::SUCCESS, $exitCode);
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

        $this->assertEquals(Command::INVALID, $exitCode);
        $this->assertStringContainsString('Unable to find role', Artisan::output());
    }
}
