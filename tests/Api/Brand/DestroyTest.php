<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Account;
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
        $server = $this->transformHeadersToServerVars(
            [
                'X-API-TOKEN' => Account::getSystemAccount()->token,
            ]
        );
        $response = $this->actingAs($user)->call('DELETE', self::getURLWithId($brand->id), [], [], [], $server);

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

    /**
     * @return void
     */
    public function testStatusCodeInvalidRequest()
    {
        $this->initWithInvalidResponse();
        $this->assertEquals(404, $this->statusCode);
    }

    public function initWithInvalidResponse()
    {
        $user = User::find(1);
        $server = $this->transformHeadersToServerVars(
            [
                'X-API-TOKEN' => Account::getSystemAccount()->token,
            ]
        );

        $response = $this->actingAs($user)->call('DELETE', self::URL.'/200', [], [], [], $server);

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

    /**
     * @return void
     */
    public function testResponseInvalidRequest()
    {
        $this->initWithInvalidResponse();
        $obj = json_decode($this->content);

        $this->assertTrue(
            property_exists($obj, 'message')
        );

        $this->assertFalse($obj->message->success);
    }
}
