<?php

namespace tests\Api\Ticket;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\StoreTestHelper;
use tests\TestCase;

class StoreTest extends TestCase
{
    use StoreTestHelper;
    use DatabaseTransactions;

    const URL = '/api/v1/tickets';

    public function testValidationErrors()
    {
        $response = $this->executeCall([]);

        $this->assertStringContainsString(
            'The ip field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $ticket = factory(Ticket::class)->make()->toArray();

        $response = $this->executeCall($ticket);

        //$this->assertTrue(
        //    $response->isSuccessful()
        //);

        //dd($response);

        $obj = $response->getContent();

        foreach ($ticket as $key => $value) {
            $this->assertStringContainsString(
                $key,
                $obj
            );
        }
    }
}
