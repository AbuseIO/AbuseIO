<?php

namespace tests\Api\Account;

use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/accounts/';

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
        $account1 = factory(Account::class)->create();
        $account2 = factory(Account::class)->make();

        $response = $this->call(['name' => $account2->name, 'brand_id' => $account1->brand_id], $account1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Account::find($account1->id)->name,
            $account2->name
        );
    }

    public function call($parameters, $id = 1)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X-API-TOKEN' => Account::getSystemAccount()->token,
            ]);

        return parent::call('PUT', $this->getUri($id), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }
}
