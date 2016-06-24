<?php

namespace tests\Api\Account;

use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use tests\TestCase;

class StoreTest extends TestCase
{
    const URL = '/api/v1/accounts';

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
        $account = factory(Account::class)->make()->toArray();

        $response = $this->call($account);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $obj = json_decode($response->getContent());

        //dd($obj);
        $this->assertTrue($account['name'] == $obj->data->name);
    }

    public function call($parameters)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X_API_TOKEN' => Account::getSystemAccount()->token,
            ]);

        return parent::call('POST', self::URL, $parameters, [], [], $server);
    }
}
