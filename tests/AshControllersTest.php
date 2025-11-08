<?php

namespace tests;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AshControllersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function testAshCollectOne()
    {
        $this->withTicketId(1);
    }

    /**
     * @return void
     */
    public function testAshCollectTwo()
    {
        $this->withTicketId(2);
    }

    private function withTicketId($id)
    {
        // Ensure a ticket exists with ash_token_ip populated
        $ticket = Ticket::find($id);
        if (!$ticket) {
            // Create contacts to satisfy factory requirements and generate ash token via observer
            Contact::factory()->create();
            Contact::factory()->create();
            $ticket = Ticket::factory()->create();
            $id = $ticket->id;
        }

        $uri = sprintf('/ash/collect/%d/%s', $id, $ticket->ash_token_ip);

        $response = $this->call('GET', $uri);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
