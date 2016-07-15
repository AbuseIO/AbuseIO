<?php

namespace tests\Api\Ticket;

use AbuseIO\Models\Account;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/tickets/';

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
        $ticket1 = factory(Ticket::class)->create();
        $ticket2 = factory(Ticket::class)->make()->toArray();

        $ticket2['last_notify_timestamp'] = $ticket2['last_notify_timestamp'];

        $response = $this->call($ticket2, $ticket1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Ticket::find($ticket1->id)->ip,
            $ticket2['ip']
        );
    }

    public function testUpdateWithMissingPropertyName()
    {
        $ticket1 = factory(Ticket::class)->create();
        $ticket2 = factory(Ticket::class)->make()->toArray();

        unset($ticket2['ip']);

        $ticket2['last_notify_timestamp'] = $ticket2['last_notify_timestamp'];

        $response = $this->call($ticket2, $ticket1->id);

        $this->assertFalse(
            $response->isSuccessful()
        );

        $this->assertContains(
            'The ip field is required.',
            $response->getContent()
        );
    }

    public function call($parameters, $id = 1)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X_API_TOKEN' => Account::getSystemAccount()->token,
            ]);

        return parent::call('PUT', $this->getUri($id), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }
}
