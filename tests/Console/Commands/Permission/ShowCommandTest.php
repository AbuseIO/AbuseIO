<?php

namespace tests\Console\Commands\Permission;

use AbuseIO\Models\Permission;
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
    public function testPermissionShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('permission:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "permission").', $output);
        $this->assertStringContainsString('Shows a permission based on the provided ID', $output);
    }

    #[group('functional')]
    public function testPermissionShowCommandShouldPassWithValidIdFilter(): void
    {
        $headers = ['Id',  'Name', 'Description'];
        $permission = Permission::factory()->create();

        $exitCode = Artisan::call(
            'permission:show',
            [
                'permission' => $permission->id,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    #[group('functional')]
    public function testPermissionShowCommandShouldFailWithInvalidIdFilter(): void
    {
        $exitCode = Artisan::call(
            'permission:show',
            [
                'permission' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching permission was found.', Artisan::output());
    }
}
