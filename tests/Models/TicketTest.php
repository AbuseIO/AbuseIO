<?php

namespace tests\Models;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    #[group('integration')]
    public function testTicketModelSaveEventInTicketApiTokenProvider()
    {
        $ticket = Ticket::factory()->make();
        $this->assertNull($ticket->api_token);
        $ticket->save();
        $this->assertNotNull($ticket->api_token);
    }
}
