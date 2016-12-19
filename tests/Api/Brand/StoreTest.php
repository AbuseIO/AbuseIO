<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Brand;
use tests\Api\StoreTestHelper;
use tests\TestCase;

class StoreTest extends TestCase
{
    use StoreTestHelper;

    const URL = '/api/v1/brands';

    public function testValidationErrors()
    {
        $response = $this->executeCall([]);

        $this->assertContains(
            'The name field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $brand = factory(Brand::class)->make()->toArray();

        unset($brand['logo']);
        unset($brand['creator_id']);

//        $response = $this->executeCall($brand);

//        dd($response->getContent());
//
//        $this->assertTrue(
//            $response->isSuccessful()
//        );
//
//        $obj = json_decode($response->getContent());
//
//        dd($obj->data);
    }
}
