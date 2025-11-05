<?php

namespace tests\Api;

use tests\TestCase;

class ApiVersionTest extends TestCase
{
    const URL = '/api/getversioninfo';
    protected $statusCode;
    protected $content;

    public function testApiVersionCall()
    {
        $this->executeCall();
        $response = json_decode($this->content, true);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('version', $response);
        $this->assertEquals('v1', $response['version']);
    }

    protected function executeCall()
    {
        $server = $this->transformHeadersToServerVars([]);

        $response = $this->call('GET', self::URL, [], [], [], $server);

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }
}
