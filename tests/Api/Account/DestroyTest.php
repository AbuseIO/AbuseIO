<?php

namespace tests\Api\Account;

use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use tests\Api\DestroyTestHelper;
use tests\TestCase;

class DestroyTest extends TestCase
{
    use DestroyTestHelper;

    const URL = '/api/v1/accounts';

    public function initWithValidResponse()
    {
        $user = User::find(1);

        $account = factory(Account::class)->create();

        $server = $this->transformHeadersToServerVars(
            [
                'X-API-TOKEN' => Account::getSystemAccount()->token,
            ]
        );

        $response = $this->actingAs($user)->call('DELETE', self::getURLWithId($account->id), [], [], [], $server);

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
                'Accept'      => 'application/json',
            ]
        );
        $response = $this->actingAs($user)->call('DELETE', self::URL.'/200', [], [], [], $server);

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();

        return $response;
    }

    /**
     * @return void
     */
    public function testResponseInvalidRequest()
    {
        $result = $this->initWithInvalidResponse()->decodeResponseJson();

        $this->assertTrue(
            array_key_exists('message', $result)
        );

        $this->assertFalse($result['message']['success']);
    }
}
