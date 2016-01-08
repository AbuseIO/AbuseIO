<?php

namespace tests\Console\Commands\Event;

use Illuminate\Support\Facades\Artisan;
use \TestCase;

class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('event:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ["Id", "Source", "Ticket id", "Information"];
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
        $this->assertContains("Simon Says", $output);
        $this->assertContains("DNS project", $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call('event:list', [
            "--filter" => "Simon says"
        ]);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains("Simon Says", $output);
        $this->assertNotContains("DNS project", $output);
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call('event:list', [
            "--filter" => "xxx"
        ]);

        $this->assertEquals($exitCode, 0);
        $this->assertContains("No event found for given filter.", Artisan::output());
    }
}

