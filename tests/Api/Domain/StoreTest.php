<?php

namespace tests\Api\Domain;

use AbuseIO\Models\Domain;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/d41d8cd98f00b204e8000998ecf8427e/v1/domains';

    public function testValidationErrors()
    {
        $response = $this->call([]);

        $this->assertContains(
            'The name field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $domain = factory(Domain::class)->make()->toArray();

        $response = $this->call($domain);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $obj = $response->getContent();

        unset($domain['contact_id']);

        foreach ($domain as $key => $value) {
            $this->assertContains(
                $key,
                $obj
            );
        }
    }

    public function call($parameters)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(['Accept' => 'application/json']);

        return parent::call('POST', self::URL, $parameters, [], [], $server);
    }
}
