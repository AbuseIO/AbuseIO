<?php

namespace tests\Console\Commands\Netblock;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

/**
 * Class EditCommandTest
 * @package tests\Console\Commands\Netblock
 */
class EditCommandTest extends TestCase
{
    public function testWithoutId()
    {
        $exitCode = Artisan::call('netblock:edit');
        $this->assertEquals($exitCode, 0);
        $this->assertContains("The required id argument was not passed, try --help", Artisan::output());
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'netblock:edit',
            [
                "--id" => "10000"
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains("Unable to find netblock with this criteria", Artisan::output());
    }

    public function testWithInvalidContact()
    {
        $exitCode = Artisan::call(
            'netblock:edit',
            [
                "--id" => "1",
                "--contact" => "1000"
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains("Unable to find contact with this criteria", Artisan::output());
    }

    public function testEnabled()
    {
        $exitCode = Artisan::call(
            'netblock:edit',
            [
                "--id" => "1",
                "--enabled" => "false"
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains("Netblock has been successfully updated", Artisan::output());
        /**
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('NetblocksTableSeeder');
    }
}
