<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testRoleEditingShouldFailWithoutParameters(): void
    {
        $exitCode = Artisan::call('role:edit');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Edits an existing role', $output);
    }

    #[group('functional')]
    public function testRoleEditingShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'role:edit',
            [
                'id' => '10000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find role with this criteria', Artisan::output());
    }

    #[group('integration')]
    public function testRoleEditingCommandShouldPassWithValidArguments(): void
    {
        $role = Role::create([
            'name'    => 'some role',
            'description' => 'some description',
        ]);

        $exitCode = Artisan::call(
            'role:edit',
            [
                'id'     => $role->id,
                '--name' => 'some bogus value',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The role has been updated', Artisan::output());
        $this->assertEquals(
            'some bogus value',
            Role::find($role->id)->name
        );
    }
}
