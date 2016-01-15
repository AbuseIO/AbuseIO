<?php

namespace tests\Console\Commands\Evidence;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('evidence:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Filename', 'Sender', 'Subject', 'Created at'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call('evidence:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('i say 1', $output);
        $this->assertContains('i say 2', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'evidence:list',
            [
                '--filter' => '1 me',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('i say 1', $output);
        $this->assertNotContains('i say 2', $output);
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'evidence:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No evidence found for given filter.', Artisan::output());
    }
}
