<?php

namespace tests\Api\Netblock;

use AbuseIO\Models\Netblock;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/netblocks/';

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
        $netblock1 = factory(Netblock::class)->create();
        $netblock2 = factory(Netblock::class)->make()->toArray();

        $response = $this->call($netblock2, $netblock1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $description = Netblock::find($netblock1->id)->description;

        $this->assertEquals(
            $description,
            $netblock2['description'],
            sprintf('netblock1->description:%s , netblock2->description:%s', $description, $netblock2['description'])
        );
    }

    public function testUpdateWithMissingProperty()
    {
        $netblock1 = factory(Netblock::class)->create();
        $netblock2 = factory(Netblock::class)->make()->toArray();

        unset($netblock2['description']);

        $response = $this->call($netblock2, $netblock1->id);

        $this->assertFalse(
            $response->isSuccessful()
        );

        $this->assertContains(
            'The description field is required.',
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
