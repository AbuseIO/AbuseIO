<?php

namespace tests\Api\Ticket;

use AbuseIO\Models\Account;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/tickets';

    public function testValidationErrors()
    {
        $response = $this->call([]);

        $this->assertContains(
            'The ip field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $ticket = factory(Ticket::class)->make()->toArray();

        $response = $this->call($ticket);

        //$this->assertTrue(
        //    $response->isSuccessful()
        //);

        //dd($response);

        $obj = $response->getContent();


        foreach ($ticket as $key => $value) {
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
