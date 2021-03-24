<?php

namespace tests\Console\Commands\Ticket;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The list of testing fixtures to test against.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $ticketList;

    public function initDB()
    {
        $this->ticketList = factory(Ticket::class, 10)->create();
    }

    public function testWithValidIdFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'ticket:show',
            [
                'ticket' => $this->ticketList->get(1)->id,
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
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
            'Last notify timestamp', ];

        foreach ($fields as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    public function testWithInvalidFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'ticket:show',
            [
                'ticket' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No matching ticket was found.', Artisan::output());
    }
}
