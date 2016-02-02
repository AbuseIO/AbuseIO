<?php

namespace tests\Console\Commands\Event;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
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
        $exitCode = Artisan::call('event:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('123456789@blocklist.de', $output);
        $this->assertContains('min_amplification":"1.3810', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'event:list',
            [
                '--filter' => 'Abusehub',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Abusehub', $output);
        $this->assertNotContains('regbot', $output);
    }

    public function testNotFoundFilter()
    {
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
