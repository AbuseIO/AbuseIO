<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Brand;
use tests\Api\StoreTestHelper;
use tests\TestCase;

class StoreTest extends TestCase
{
    use StoreTestHelper;

    const URL = '/api/v1/brands';

    public function testMethodNotAllowedReturns500()
    {
        // it is not possible to create a brand with the api No Method allowed;
        $response = $this->executeCall([])->decodeResponseJson();
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('success', $response['message']);
        $this->assertFalse($response['message']['success']);
    }
}
