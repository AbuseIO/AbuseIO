<?php

namespace tests\Console\Commands\Brand;

use AbuseIO\Models\Brand;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
//    public function testWithoutArguments()
//    {
    //Artisan::call('brand:create');
    // $output = Artisan::output();

    // $this->assertStringContainsString("brand:create", $output);

//        $this->assertStringContainsString('The name field is required.', $output);
//        $this->assertStringContainsString('The company name field is required.', $output);
//        $this->assertStringContainsString('The introduction text field is required.', $output);
//        $this->assertStringContainsString('Failed to create the brand due to validation warnings', $output);
//    }

    public function testCreateValid()
    {
        Artisan::call('brand:create', [
            'name'              => 'test_dummy',
            'company_name'      => 'test_company_name',
            'introduction_text' => 'abcdefg',
        ]);
        $output = Artisan::output();

        $this->assertStringContainsString('The brand has been created', $output);

        Brand::where('name', 'test_dummy')->forceDelete();
    }
}
