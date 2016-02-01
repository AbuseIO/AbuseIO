<?php

namespace tests\Models;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BrandTest extends \TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        $account = factory(Brand::class)->create();
        $accountFromDB = Brand::where("name", $account->name)->first();
        $this->assertEquals($account->name, $accountFromDB->name);
    }
}
