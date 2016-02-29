<?php

namespace tests\Console\Commands\Domain;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    /**
    * @expectedException RuntimeException
    * @expectedExceptionMessage Not enough arguments (missing: "id").
    */
    public function testWithoutId()
    {
         Artisan::call('domain:edit');
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('Unable to find domain with this criteria', Artisan::output());
    }

    public function testWithInvalidContact()
    {
        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id' => '1',
                '--contact_id' => '1000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('Unable to find contact with this criteria', Artisan::output());
    }

    public function testEnabled()
    {
        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id' => '1',
                '--enabled' => 'false',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('The domain has been updated', Artisan::output());
        /*
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('DomainsTableSeeder');
    }
}
