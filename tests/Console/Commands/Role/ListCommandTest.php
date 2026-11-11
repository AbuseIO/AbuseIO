<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
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
    public function testRoleListCommandShouldPassWithNoArguments(): void
    {
        $exitCode = Artisan::call(
            'role:list',
            [
                //
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('System Administrator', Artisan::output());
    }

    #[group('functional')]
    public function testRoleListCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'role:list',
            [
                '--filter' => 'TestRoleThatDoesNotExist',
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No roles found matching the criteria.', $output);
        $this->assertStringNotContainsString('System Administrator', $output);
    }

    #[group('functional')]
    public function testRoleListCommandShouldPassWithValidFilter(): void
    {
        $roleOne = Role::create([
            'name' => 'TestRoleThatDoesExist',
            'description' => 'Some description',
        ]);
        $roleTwo = Role::create([
            'name' => 'TestRoleThatAlsoExists',
            'description' => 'Some description',
        ]);

        $exitCode = Artisan::call(
            'role:list',
            [
                '--filter' => $roleOne->name,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($roleOne->name, $output);
        $this->assertStringNotContainsString($roleTwo->name, $output);
    }
}
