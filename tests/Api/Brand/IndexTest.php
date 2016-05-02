<?php

namespace tests\Api\Brand;

use tests\TestCase;

class IndexTest extends TestCase
{
    private $statusCode;

    private $content;

    public function setUp()
    {
        parent::setUp();

        $response = $this->call('GET', '/api/v1/brands');

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

    /**
     * @return void
     */
    public function testRouteExists()
    {
        $this->assertEquals(200, $this->statusCode);
    }

    /**
     * @return void
     */
    public function testHasDataAttribute()
    {
        $obj = json_decode($this->content);
        $this->assertTrue(property_exists($obj, 'data'));
    }

    /**
     * @return void
     */
    public function testIsValidJson()
    {
        $this->assertJson($this->content);
    }

    /**
     * @return void
     */
    public function testHasLenghtOne()
    {
        $this->assertEquals(
            count(
                json_decode($this->content)
            ),
            1
        );
    }
}
