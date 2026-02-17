<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    use RefreshDatabase;


    #[group('functional')]
    public function testRoleCreateCommandShouldFailWithoutParameters(): void
    {
        $exitCode = Artisan::call('role:create');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "name, description")', $output);
        $this->assertStringContainsString('Creates a new role', $output);
    }

    #[group('functional')]
    public function testRoleCreateCommandShouldPassWithValidArguments(): void
    {
        $exitCode = Artisan::call(
            'role:create',
            [
                'name'        => 'Some name',
                'description' => 'Some description',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Role created successfully.', Artisan::output());
    }
}
