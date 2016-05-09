<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Brand;
use AbuseIO\Models\User;
use tests\TestCase;

class StoreTest extends TestCase
{
    const URL = '/api/v1/brands';

    public function testValidationErrors()
    {
        $response = $this->call([]);

        $this->assertContains(
            'The name field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $brand = factory(Brand::class)->make()->toArray();


        $response = $this->call($brand);

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

    public function call($parameters)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(["Accept" => 'application/json']);

        return parent::call('POST', self::URL, $parameters, [], [], $server);
    }


}
