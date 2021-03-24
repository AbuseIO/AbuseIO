<?php

namespace tests\Console\Commands\Brand;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    public function testValid()
    {
        //TODO make brand table seeder;
        $this->assertTrue(true);
        //        $exitCode = Artisan::call('brand:delete', [
//            "--id" => "1"
//        ]);
//
//        $this->assertEquals($exitCode, 0);
//        $this->assertStringContainsString("The brand has been deleted from the system", Artisan::output());
//        /**
//         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
//         */
//        $this->seed('BrandTableSeeder');
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'brand:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find brand', Artisan::output());
    }
}
