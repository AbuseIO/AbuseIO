<?php

namespace tests\Api\Ticket;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\UpdateTestHelper;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    use UpdateTestHelper;

    const URL = '/api/v1/tickets/';

    public function testEmptyUpdate()
    {
        $response = $this->executeCall([]);

        $this->assertStringContainsString(
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

        $response = $this->executeCall($ticket2, $ticket1->id);

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

        $response = $this->executeCall($ticket2, $ticket1->id);

        $this->assertFalse(
            $response->isSuccessful()
        );

        $this->assertStringContainsString(
            'The ip field is required.',
            $response->getContent()
        );
    }
}
