<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Brand;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/brands/';

    public function testEmptyUpdate()
    {
        $response = $this->call([]);

        $this->assertContains(
            'AbuseIO',
            $response->getContent()
        );

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testUpdateName()
    {
        $brand1 = factory(Brand::class)->create();
        $brand2 = factory(Brand::class)->make();

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

        $server = $this->transformHeadersToServerVars(['Accept' => 'application/json']);

        return parent::call('PUT', $this->getUri($id), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }
}
