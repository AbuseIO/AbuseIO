<?php

namespace tests\Console\Commands\Permission;

use AbuseIO\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testPermissionListCommandShouldPassToShowTableHeaders(): void
    {
        $headers = ['Id', 'Name', 'Description'];

        $exitCode = Artisan::call('permission:list', []);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testPermissionListCommandShouldPassShowingTwoPermissions(): void
    {
        $permissionOne = Permission::create([
            'name' => 'test-permission-one',
            'description' => 'A permission for testing purposes',
        ]);
        $permissionTwo = Permission::create([
            'name' => 'test-permission-two',
            'description' => 'A permission for testing purposes',
        ]);

        $exitCode = Artisan::call('permission:list', []);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($permissionOne->name, $output);
        $this->assertStringContainsString($permissionTwo->name, $output);
    }

    #[group('functional')]
    public function testPermissionListCommandShouldPassWithValidFilter(): void
    {
        $permissionOne = Permission::create([
            'name' => 'test-permission-one',
            'description' => 'A permission for testing purposes',
        ]);
        $permissionTwo = Permission::create([
            'name' => 'test-permission-two',
            'description' => 'A permission for testing purposes',
        ]);

        $exitCode = Artisan::call(
            'permission:list',
            [
                '--filter' => $permissionOne->id,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($permissionOne->id, $output);
        $this->assertStringNotContainsString($permissionTwo->id, $output);
    }

    #[group('functional')]
    public function testPermissionListCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'permission:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No permissions found.', Artisan::output());
    }
}
