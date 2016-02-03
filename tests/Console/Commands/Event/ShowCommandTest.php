<?php

namespace tests\Console\Commands\Event;

use AbuseIO\Models\Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
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

    public function testWithValidIdFilter()
    {
        $this->initDB();

        $exitCode = Artisan::call(
            'event:show',
            [
                'event' => $this->eventList->get(0)->id,
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Id', 'Ticket id', 'Evidence id', 'Source', 'Timestamp', 'Information'] as $el) {
            $this->assertContains($el, $output);
        }
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'event:show',
            [
                'event' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No matching event was found.', Artisan::output());
    }
}
