<?php

namespace tests\Models;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class TicketTest extends TestCase
{
    use DatabaseTransactions;

    public function testTicketModelSaveEventInTicketApiTokenProvider()
    {
        $ticket = factory(Ticket::class)->make();
        $this->assertNull($ticket->api_token);
        $ticket->save();
        $this->assertNotNull($ticket->api_token);
    }
}
