<?php

namespace tests\Console\Commands\Domain;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    public function testValid()
    {
        $exitCode = Artisan::call(
            'domain:delete',
            [
                'id' => '1',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('domain has been deleted', Artisan::output());
        /*
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('DomainsTableSeeder');
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'domain:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find domain', Artisan::output());
    }
}
