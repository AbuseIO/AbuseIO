<?php

namespace tests\Console\Commands\Event;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call(
            'event:show',
            [
                'event' => '1',
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
