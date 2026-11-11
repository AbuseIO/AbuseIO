<?php

namespace tests\Console\Commands\Ticket;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 *
 * @note In this test we use the ticket factory to create tickets, the model itself is really large to set up so we use factories to keep the test code clean.
 */
class DeleteCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testTicketDeleteCommandShouldFailWithoutId(): void
    {
        $exitCode = Artisan::call('ticket:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Deletes a ticket from the system', $output);
    }

    #[group('functional')]
    public function testTicketDeleteCommandShouldPassWithValidId(): void
    {
        $ticket = Ticket::factory()->create();

        $exitCode = Artisan::call('ticket:delete', [
            'id' => $ticket->id,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The ticket has been deleted from the system', Artisan::output());
    }

    #[group('functional')]
    public function testTicketDeleteCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'ticket:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find ticket', Artisan::output());
    }
}
