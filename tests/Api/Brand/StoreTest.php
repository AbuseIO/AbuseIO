<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Brand;
use AbuseIO\Models\User;
use tests\TestCase;

class StoreTest extends TestCase
{
    const URL = '/api/v1/brands';

    public function testWithValidResponse()
    {
        $user = User::find(1);

        $brand = factory(Brand::class)->make();

        $response = $this->actingAs($user)->call('POST', self::URL, $brand->toArray());

        //dd($response->getContent());
        $this->assertTrue(true);
    }
}


