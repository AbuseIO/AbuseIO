<?php

namespace tests\Console\Commands\Brand;

use AbuseIO\Models\Brand;
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
        Artisan::call('brand:edit');
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'brand:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('Unable to find brand with this criteria', Artisan::output());
    }

    public function testName()
    {
        $this->assertEquals('AbuseIO', Brand::find(1)->name);

        $exitCode = Artisan::call(
            'brand:edit',
            [
                'id'     => '1',
                '--name' => 'New name',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('The brand has been updated', Artisan::output());

        $brand = Brand::find(1);
        $this->assertEquals('New name', $brand->name);
        $brand->name = 'AbuseIO';
        $brand->save();
    }

    public function testCompanyName()
    {
        $this->assertEquals('AbuseIO', Brand::find(1)->company_name);

        $exitCode = Artisan::call(
            'brand:edit',
            [
                'id'             => '1',
                '--company_name' => 'New name 1',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('The brand has been updated', Artisan::output());

        $brand = Brand::find(1);

        $this->assertEquals('New name 1', $brand->company_name);
        $brand->company_name = 'AbuseIO';
        $brand->save();
    }
}
