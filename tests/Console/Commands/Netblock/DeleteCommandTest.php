<?php

namespace tests\Console\Commands\Netblock;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class DeleteCommandTest extends TestCase{

    public function testValid()
    {
        $exitCode = Artisan::call('netblock:delete', [
            "id" => "1"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("netblock has been deleted", Artisan::output());
        /**
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        $this->seed('NetblocksTableSeeder');
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call('netblock:delete', [
            "id" => "1000"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Unable to find netblock", Artisan::output());
    }
}
