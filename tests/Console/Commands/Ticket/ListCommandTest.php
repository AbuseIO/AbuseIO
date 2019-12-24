<?php

namespace tests\Console\Commands\Ticket;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The list of testing fixtures to test against.
     *
     * @var Illuminate\Database\Eloquent\Collection
     */
    private $ticketList;

    public function initDB()
    {
        $this->ticketList = factory(Ticket::class, 10)->create();
    }

    public function testHeaders()
    {
        $exitCode = Artisan::call(
            'ticket:list',
            [
                //
            ]
        );

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Ip', 'Domain', 'Class id', 'Type id'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'ticket:list',
            [
                //
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertStringContainsString($this->ticketList->get(0)->domain, $output);
        $this->assertStringContainsString($this->ticketList->get(0)->ip, $output);
    }

    public function testFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'ticket:list',
            [
                '--filter' => $this->ticketList->get(0)->id,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertStringContainsString($this->ticketList->get(0)->ip, $output);
        $this->assertStringNotContainsString($this->ticketList->get(1)->domain, $output);
    }

    public function testNotFoundFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'ticket:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No ticket found for given filter.', Artisan::output());
    }

    public function testJson()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'ticket:list',
            [
                '--json' => 'true',
            ]
        );

        $this->assertEquals($exitCode, 0);

        json_decode(Artisan::output());
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
    }
}
