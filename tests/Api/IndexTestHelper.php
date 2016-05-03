<?php

namespace tests\Api;

use AbuseIO\Models\User;

trait IndexTestHelper
{
    private $statusCode;

    private $content;

    public function setUp()
    {
        parent::setUp();

        $user = User::find(1);

        $response = $this->actingAs($user)->call('GET', self::URL);

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
