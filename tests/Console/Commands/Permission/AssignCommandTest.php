<?php

namespace Console\Commands\Permission;

use AbuseIO\Models\Permission;
use AbuseIO\Models\Role;
use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class AssignCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testPermissionRoleAssignmentShouldFaiWithInvalidOptions(): void
    {
        $exitCode = Artisan::call('permission:assign', [
            '--permission' => 'non-existing-permission',
            '--role' => 'non-existing-role',
        ]);

        $this->assertEquals(1, $exitCode);
    }

    #[group('functional')]
    public function testPermissionRoleAssignmentShouldFailWithMissingOptions(): void
    {
        $exitCode = Artisan::call('permission:assign', [
            // missing options
        ]);

        $this->assertEquals(1, $exitCode);
    }

    #[group('functional')]
    public function testRoleAssignmentShouldPassWithValidIds(): void
    {
        $permission = Permission::factory()->create();
        $role = Role::create([
            'name' => 'test-role',
            'description' => 'A role for testing purposes',
        ]);

        $exitCode = Artisan::call('permission:assign', [
            '--permission' => $permission->id,
            '--role' => $role->id,
        ]);

        $this->assertEquals(0, $exitCode);
    }
}
