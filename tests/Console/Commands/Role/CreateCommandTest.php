<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        /** @var Role $dummy */
        $dummy = Role::factory()->make();

        $exitCode = Artisan::call(
            'role:create',
            [
                'name'        => $dummy->name,
                'description' => $dummy->description,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('created', Artisan::output());

        // Detach permissions before hard-deleting to avoid FK constraint violations
        Role::where([
            'name'        => $dummy->name,
            'description' => $dummy->description,
        ])->get()->each(function (Role $role) {
            $role->permissions()->detach();
            $role->forceDelete();
        });
    }

    public function testWithoutParams()
    {
        ob_start();
        $exitCode = Artisan::call('role:create');
        // The command shows help via the runtime-exception helper and returns failure
        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Creates a new role', ob_get_clean());
    }
}
