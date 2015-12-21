<?php

namespace tests\Console\Commands\Brand;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class DeleteCommandTest extends TestCase{


    public function testValid()
    {
        $exitCode = Artisan::call('brand:delete', [
            "--id" => "2"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("The brand has been deleted from the system", Artisan::output());
        /**
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        //$this->seed('BrandsTableSeeder');
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call('brand:delete', [
            "--id" => "1000"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("Unable to find brand", Artisan::output());
    }
}

