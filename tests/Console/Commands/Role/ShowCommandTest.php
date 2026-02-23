<?php

namespace tests\Console\Commands\Role;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testRoleShowShouldFailWithoutArgument(): void
    {
        $exitCode = Artisan::call('role:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "role")', $output);
        $this->assertStringContainsString('Shows a role based on the provided ID or name', $output);
    }

    #[group('functional')]
    public function testRoleShowShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'role:show',
            [
                'role' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching role was found.', Artisan::output());
    }

    #[group('functional')]
    public function testRoleShowShouldPassWithValidNameFilter(): void
    {
        $exitCode = Artisan::call(
            'role:show',
            [
                'role' => 'Admin',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Admin', Artisan::output());
    }
}
