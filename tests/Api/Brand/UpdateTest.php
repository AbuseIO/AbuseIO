<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/brands/';
// TODO fix logo problem in FromRequest so controller can execute BrandFromRequest before entering controller;
//    public function testEmptyUpdate()
//    {
//        $response = $this->call([]);
//
//        $this->assertContains(
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

        $response = $this->call(['name' => $brand2->name], $brand1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Brand::find($brand1->id)->name,
            $brand2->name
        );
    }

    public function call($parameters, $id = 1)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X_API_TOKEN' => Account::getSystemAccount()->token,
            ]);

        return parent::call('PUT', $this->getUri($id), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }
}
