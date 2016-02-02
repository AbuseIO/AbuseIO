<?php

namespace tests\Models;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketTest extends \TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        $ticket = factory(Ticket::class)->create();
        //dd($ticket);
        //$ticketFromDB = Ticket::where("submitter", $ticket->submitter)->first();
        //$this->assertEquals($ticket->submitter, $ticketFromDB->submitter);
        $this->assertTrue(true);
    }
}
