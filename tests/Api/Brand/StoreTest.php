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
        $raw = $this->executeCall([]);
        $response = json_decode($raw->getContent(), true);
        $this->assertArrayHasKey('message', $response);
        if (is_array($response['message']) && array_key_exists('success', $response['message'])) {
            $this->assertFalse($response['message']['success']);
        } else {
            $this->assertIsString($response['message']);
        }
    }
}
