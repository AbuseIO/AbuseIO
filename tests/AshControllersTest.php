<?php

namespace tests;

use AbuseIO\Models\Ticket;

class AshControllersTest extends TestCase
{
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
        $uri = sprintf(
            '/ash/collect/%d/%s',
            $id,
            Ticket::find($id)->ash_token_ip
        );

        $response = $this->call('GET', $uri);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
