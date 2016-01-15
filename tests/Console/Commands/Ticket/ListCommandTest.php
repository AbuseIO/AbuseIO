<?php

namespace tests\Console\Commands\Ticket;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
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
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call(
            'ticket:list',
            [
                //
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('domain13', $output);
        $this->assertContains('10.1.11.77', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'ticket:list',
            [
                '--filter' => '1',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('domain13', $output);
        $this->assertNotContains('10.1.11.77', $output);
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'ticket:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No ticket found for given filter.', Artisan::output());
    }
}
