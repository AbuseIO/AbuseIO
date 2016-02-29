<?php

namespace tests\Console\Commands\Event;

use AbuseIO\Models\Event;
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
     * the list of test fixture to test against
     * @var Illuminate\Database\Eloquent\Collection
     */
    private $eventList;

    private function initDB()
    {
        $this->eventList = factory(Event::class, 10)->create();
    }


    public function testHeaders()
    {
        $exitCode = Artisan::call('event:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Source', 'Ticket id', 'Information'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $this->initDB();

        $exitCode = Artisan::call('event:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains($this->eventList->get(0)->source, $output);
        //$this->assertContains('min_amplification":"1.3810', $output);
    }

    public function testFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'event:list',
            [
                '--filter' => $this->eventList->get(2)->source,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains($this->eventList->get(2)->source, $output);
        $this->assertNotContains($this->eventList->get(0)->source, $output);
    }

    public function testNotFoundFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'event:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No event found for given filter.', Artisan::output());
    }
}
