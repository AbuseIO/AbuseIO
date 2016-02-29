<?php

namespace tests\Console\Commands\Role;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use AbuseIO\Models\Role;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
    * @expectedException RuntimeException
    * @expectedExceptionMessage Not enough arguments (missing: "id").
    */
    public function testWithoutId()
    {
         Artisan::call('role:edit');
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
        $this->assertContains('Unable to find role with this criteria', Artisan::output());
    }



    public function testEnabled()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'role:edit',
            [
                'id' => $this->role->id,
                '--name' => 'some bogus value',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('The role has been updated', Artisan::output());

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
