<?php

namespace tests\Console\Commands\Ticket;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ListCommandTest.
 *
 * @note In this test we use the ticket factory to create tickets, the model itself is really large to set up so we use factories to keep the test code clean.
 */
class ListCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testTicketListCommandShouldPassToShowTableHeaders(): void
    {
        $headers = ['Id', 'Ip', 'Domain', 'Class id', 'Type id'];
        Ticket::factory()->create();

        $exitCode = Artisan::call(
            'ticket:list',
            [
                //
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testTicketListCommandShouldPassShowingTickets(): void
    {
        $ticket = Ticket::factory()->create();

        $exitCode = Artisan::call(
            'ticket:list',
            [
                //
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($ticket->ip, $output);
        $this->assertStringContainsString($ticket->domain, $output);
    }


    #[group('functional')]
    public function testTicketListCommandShouldPassWithValidIdFilter(): void
    {
        $ticket = Ticket::factory()->create();

        $exitCode = Artisan::call(
            'ticket:list',
            [
                '--filter' => $ticket->id,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($ticket->ip, $output);
        $this->assertStringContainsString($ticket->domain, $output);
    }

    #[group('integration')]
    public function testTicketListCommandShouldFailWithInvalidIdFilter(): void
    {
        $exitCode = Artisan::call(
            'ticket:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No tickets found.', Artisan::output());
    }

    #[group('functional')]
    public function testTicketListCommandShouldPassWithJsonFilterTrue(): void
    {
        Ticket::factory()->create();

        $exitCode = Artisan::call(
            'ticket:list',
            [
                '--json' => 'true',
            ]
        );
        json_decode(Artisan::output());

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }
}
