<?php

namespace tests\Api\Netblock;

use AbuseIO\Models\Account;
use AbuseIO\Models\Netblock;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/netblocks';

    public function testValidationErrors()
    {
        $response = $this->call([]);

        $this->assertContains(
            'The first ip field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $netblock = factory(Netblock::class)->make()->toArray();

        $response = $this->call($netblock);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $obj = $response->getContent();

        unset($netblock['contact_id']);
        unset($netblock['first_ip_int']);
        unset($netblock['last_ip_int']);

        foreach ($netblock as $key => $value) {
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

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X_API_TOKEN' => Account::getSystemAccount()->token,
            ]);

        return parent::call('POST', self::URL, $parameters, [], [], $server);
    }
}
