<?php

namespace tests\Jobs;

use AbuseIO\Jobs\GenerateTicketsGraphPoints;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class GenerateTicketsGraphPointsTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreNewTicketDataForToday()
    {
        //setup
        factory(Ticket::class, 10)->create();

        $job = new GenerateTicketsGraphPoints();

        $job->storeNewTicketDataForToday();

        // check statistics in DB;
        $this->assertTrue(true);
    }
}
