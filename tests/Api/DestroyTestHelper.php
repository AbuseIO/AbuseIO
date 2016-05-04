<?php

namespace tests\Api;

use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

trait DestroyTestHelper
{
    use DatabaseTransactions;

    private $statusCode;

    private $content;

    /**
     * @return void
     */
    public function testStatusCodeValidRequest()
    {
        $this->initWithValidResponse();
        $this->assertEquals(200, $this->statusCode);

        $obj = json_decode($this->content);
        $this->assertTrue($obj->message->success);
    }



    /**
     * @return void
     */
    public function testHasDataAttribute()
    {
        $this->initWithValidResponse();
        $obj = json_decode($this->content);
        $this->assertTrue(property_exists($obj, 'data'));
    }

    /**
     * @return void
     */
    public function testIsValidJson()
    {
        $this->initWithValidResponse();
        $this->assertJson($this->content);
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

        $response = $this->actingAs($user)->call('DELETE', self::URL.'/200');

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
