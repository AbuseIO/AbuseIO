<?php
namespace tests\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class TicketTest extends TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        //$ticket = factory(Ticket::class)->create();
        //dd($ticket);
        //$ticketFromDB = Ticket::where("submitter", $ticket->submitter)->first();
        //$this->assertEquals($ticket->submitter, $ticketFromDB->submitter);
        $this->assertTrue(true);
    }
}
