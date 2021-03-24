<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\UpdateTestHelper;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    use UpdateTestHelper;

    const URL = '/api/v1/brands/';
    // TODO fix logo problem in FromRequest so controller can execute BrandFromRequest before entering controller;
    //    public function testEmptyUpdate()
    //    {
    //        $response = $this->executeCall([]);
    //
    //        $this->assertStringContainsString(
    //            'ERR_WRONGARGS',
    //            $response->getContent()
    //        );
    //
    //        $this->assertEquals($response->getStatusCode(), 422);
    //    }

    public function testUpdate()
    {
        $brand1 = factory(Brand::class)->create();
        $brand2 = factory(Brand::class)->make();

        //        $brandArray = $brand2->toArray();

        $response = $this->executeCall(['name' => $brand2->name], $brand1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Brand::find($brand1->id)->name,
            $brand2->name
        );
    }
}
