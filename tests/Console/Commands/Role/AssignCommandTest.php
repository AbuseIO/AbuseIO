<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class AssignCommandTest.
 */
class AssignCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testRoleAssignCommandShouldFailWithoutParams(): void
    {
        $exitCode = Artisan::call('role:assign');

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Assign a role to a user', Artisan::output());
    }

    #[group('functional')]
    public function testRoleAssignCommandShouldPassWithValidParams(): void
    {
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'account_id' => 1,
            'locale' => 'en',
            'disabled' => false,
        ]);

        $exitCode = Artisan::call('role:assign', [
            '--role' => 1,
            '--user' => $user->id,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString(sprintf('The role Admin has been granted to user %s', $user->email), Artisan::output());
    }

    #[group('functional')]
    public function testRoleAssignCommandShouldFailWithInvalidRoleId(): void
    {
        $exitCode = Artisan::call('role:assign', [
            '--role' => 9999,
            '--user' => 1,
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find role with this criteria', Artisan::output());
    }

    #[group('functional')]
    public function testRoleAssignCommandShouldFailWithInvalidUserId(): void
    {
        $exitCode = Artisan::call('role:assign', [
            '--role' => 1,
            '--user' => 9999,
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find user with this criteria', Artisan::output());
    }
}
