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
            'reference',
            $response->getContent()
        );

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testUpdateName()
    {
        $netblock1 = factory(Netblock::class)->create();
        $netblock2 = factory(Netblock::class)->make();

        $response = $this->call(['name' => $netblock2->name], $netblock1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Netblock::find($netblock1->id)->name,
            $netblock2->name
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
