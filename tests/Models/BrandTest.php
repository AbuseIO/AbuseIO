<?php

namespace tests\Models;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class BrandTest extends TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        $account = factory(Brand::class)->create();
        $accountFromDB = Brand::where('name', $account->name)->first();
        $this->assertEquals($account->name, $accountFromDB->name);
    }

    public function testBrandGetLogoPath()
    {
        $b = Brand::getSystemBrand();
        $logo = $b->getLogoPath();

        // check if the image file is created
        $this->assertFileExists($logo);

        // remove the file
        unlink($logo);
    }
}
