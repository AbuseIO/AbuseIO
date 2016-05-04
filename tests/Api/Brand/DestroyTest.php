<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Brand;
use AbuseIO\Models\User;
use tests\Api\DestroyTestHelper;
use tests\TestCase;

class DestroyTest extends TestCase
{
    use DestroyTestHelper;

    const URL = '/api/v1/brands';

    public function initWithValidResponse()
    {
        $user = User::find(1);

        $brand = factory(Brand::class)->create();

        $response = $this->actingAs($user)->call('DELETE', self::getURLWithId($brand->id));

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

    private static function getURLWithId($id)
    {
        return sprintf('%s/%s', self::URL, $id);
    }

    public function testWithUndeleteable()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->call('DELETE', self::getURLWithId(1));

        $this->assertEquals(403, $response->getStatusCode());

        $this->assertJson($response->getContent());
    }
}
