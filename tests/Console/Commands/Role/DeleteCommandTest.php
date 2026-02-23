<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testDeleteRoleCommandShouldFailWithoutId(): void
    {
        $exitCode  = Artisan::call('role:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "role").', $output);
        $this->assertStringContainsString('Deletes a role from the system', $output);
    }

    #[group('functional')]
    public function testDeleteRoleCommandShouldPassWithValidId(): void
    {
        $role = Role::create([
            'name'    => 'some role',
            'description' => 'some description',
        ]);

        $exitCode = Artisan::call(
            'role:delete',
            [
                'role' => $role->id,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The role has been deleted', Artisan::output());
    }

    #[group('functional')]
    public function testRoleDeletionCommandWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'role:delete',
            [
                'role' => '100000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find role', Artisan::output());
    }
}
