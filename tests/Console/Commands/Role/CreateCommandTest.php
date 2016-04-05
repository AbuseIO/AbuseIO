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
        $dummy = factory(Role::class)->make();

        $exitCode = Artisan::call(
            'role:create',
            [
                'name'        => $dummy->name,
                'description' => $dummy->description,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertContains('created', Artisan::output());

        Role::where([
            'name'        => $dummy->name,
            'description' => $dummy->description,
        ])->forceDelete();
    }

    public function testCreateWithoutParams()
    {
        $exitCode = Artisan::call('role:create');
        $this->assertEquals(0, $exitCode);
        $this->assertContains('The description field is required.', Artisan::output());
    }
}
