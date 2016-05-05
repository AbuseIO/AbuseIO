<?php

namespace tests\Api\Brand;

use AbuseIO\Models\Brand;
use AbuseIO\Models\User;
use tests\TestCase;

class UpdateTest extends TestCase
{
    const URL = '/api/v1/brands/1';

    public function testEmptyUpdate()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(["Accept" => 'application/json']);

        $response = $this->call('PUT', self::URL, [], [], [], $server);

        $this->assertContains(
            'AbuseIO',
            $response->getContent()
        );

        $this->assertEquals($response->getStatusCode(), 200);
    }
}
