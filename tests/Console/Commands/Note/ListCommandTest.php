<?php

namespace tests\Console\Commands\Note;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('note:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Ticket id', 'Submitter', 'text', 'Hidden', 'Viewed'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call('note:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Abusedesk', $output);
        $this->assertContains('Domain Contact', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'note:list',
            [
                '--filter' => 'Abusedesk',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Abusedesk', $output);
        $this->assertNotContains('Domain Contact', $output);
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'note:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No note found for given filter.', Artisan::output());
    }
}
