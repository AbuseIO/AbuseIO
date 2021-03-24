<?php

namespace tests\Console\Commands\Role;

use AbuseIO\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use DatabaseTransactions;

    public function testWithoutId()
    {
        ob_start();
        Artisan::call('role:edit');
        $this->assertStringContainsString('Edit a role', ob_get_clean());
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'role:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find role with this criteria', Artisan::output());
    }

    public function testEnabled()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'role:edit',
            [
                'id'     => $this->role->id,
                '--name' => 'some bogus value',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The role has been updated', Artisan::output());

        $this->assertEquals(
            Role::find($this->role->id)->name,
            'some bogus value'
        );
    }

    private function initDB()
    {
        $this->role = factory(Role::class)->create();
    }
}
