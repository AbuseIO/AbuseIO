<?php

namespace tests\Api\Domain;

use AbuseIO\Models\Domain;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/domains/';

    public function testEmptyUpdate()
    {
        $response = $this->call([]);

        $this->assertContains(
            'ERR_WRONGARGS',
            $response->getContent()
        );

        $this->assertEquals($response->getStatusCode(), 422);
    }

    public function testUpdate()
    {
        $domain1 = factory(Domain::class)->create();
        $domain2 = factory(Domain::class)->make()->toArray();

        $response = $this->call($domain2, $domain1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Domain::find($domain1->id)->name,
            $domain2['name']
        );
    }

    public function testUpdateWithMissingPropertyName()
    {
        $domain1 = factory(Domain::class)->create();
        $domain2 = factory(Domain::class)->make()->toArray();

        unset($domain2['name']);

        $response = $this->call($domain2, $domain1->id);

        $this->assertFalse(
            $response->isSuccessful()
        );

        $this->assertContains(
            'The name field is required.',
            $response->getContent()
        );
    }

    public function call($parameters, $id = 1)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(['Accept' => 'application/json']);

        return parent::call('PUT', $this->getUri($id), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }
}
