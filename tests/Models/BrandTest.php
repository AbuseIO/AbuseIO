<?php

namespace tests\Models;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    #[group("functional")]
    public function testModelFactory()
    {
        $account = Brand::factory()->create();
        $accountFromDB = Brand::where('name', $account->name)->first();
        $this->assertEquals($account->name, $accountFromDB->name);
    }

    #[group("functional")]
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
