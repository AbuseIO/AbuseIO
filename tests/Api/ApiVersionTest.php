<?php

namespace tests\Api\Account;

use tests\TestCase;

class ApiVersionTest extends TestCase
{
    const URL = '/api/getversioninfo';
    protected $statusCode;
    protected $content;

    public function testApiVersionCall()
    {
        $this->executeCall();

        $response = json_decode($this->content);

        $this->assertEquals($response->version, 'v1');
    }

    protected function executeCall()
    {
        $server = $this->transformHeadersToServerVars([]);

        $response = $this->call('GET', self::URL, [], [], [], $server);

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }
}
