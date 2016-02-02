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
        $this->assertContains('customer1.tld', $output);
        $this->assertContains('10.19.3.1', $output);
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
        $this->assertContains('victim.tld', $output);
        $this->assertNotContains('192.168.2.20', $output);
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
