<?php

namespace tests\Console\Commands\Ticket;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 *
 * @note In this test we use the ticket factory to create tickets, the model itself is really large to set up so we use factories to keep the test code clean.
 */
class ShowCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testTicketShowCommandShouldFailWithNoArguments(): void
    {
        $exitCode = Artisan::call('ticket:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "ticket").', $output);
        $this->assertStringContainsString('Shows a ticket based on the provided ID', $output);
    }

    #[group('functional')]
    public function testTicketShowCommandShouldPassWithValidIdFilter(): void
    {
        $ticket = Ticket::factory()->create();
        $fields = [
            'Id',
            'Ip',
            'Domain',
            'Class id',
            'Type id',
            //                    'ip_contact_account_id',
            //                    'ip_contact_reference',
            //                    'ip_contact_name',
            //                    'ip_contact_email',
            //                    'ip_contact_api_host',
            //                    'ip_contact_auto_notify',
            //                    'ip_contact_notified_count',
            //                    'domain_contact_account_id',
            //                    'domain_contact_reference',
            //                    'domain_contact_name',
            //                    'domain_contact_email',
            //                    'domain_contact_api_host',
            //                    'domain_contact_auto_notify',
            //                    'domain_contact_notified_count',
            //                    'status_id',
            //                    'last_notify_count',
            'Last notify timestamp',
        ];

        $exitCode = Artisan::call(
            'ticket:show',
            [
                'ticket' => $ticket->id,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($fields as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    #[group('functional')]
    public function testTicketShowCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'ticket:show',
            [
                'ticket' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching ticket was found.', Artisan::output());
    }
}
