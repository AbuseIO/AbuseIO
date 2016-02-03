<?php

namespace tests\Console\Commands\Ticket;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    use DatabaseTransactions;

    /** @var  \AbuseIO\Models\Ticket $ticket */
    private $ticket;

    private function initDB()
    {
        $this->ticket = factory(Ticket::class)->create();
    }

    public function testValid()
    {
        $this->initDB();

        $exitCode = Artisan::call('ticket:delete', [
            "id" => $this->ticket->id
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("The ticket has been deleted from the system", Artisan::output());
        /**
         * I use the seeder to re-initialize the table because Artisan:call is another instance of DB
         */
        //$this->seed('AccountsTableSeeder');
    }

    public function testInvalidId()
    {
        $exitCode = Artisan::call(
            'ticket:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Unable to find ticket', Artisan::output());
    }
}
