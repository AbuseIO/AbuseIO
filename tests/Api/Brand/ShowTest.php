<?php

namespace tests\Api\Brand;

use tests\TestCase;

class ShowTest extends TestCase
{
    private $statusCode;

    private $content;

    public function initWithValidResponse()
    {
        $response = $this->call('GET', '/api/brands/1');

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

    public function initWithInvalidResponse()
    {
        $response = $this->call('GET', '/api/brands/200');

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

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
