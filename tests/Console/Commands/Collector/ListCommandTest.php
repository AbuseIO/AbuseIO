<?php

namespace tests\Console\Commands\Collector;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('collector:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Name', 'Description'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call('collector:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Rbl', $output);
        $this->assertContains('Snds', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'collector:list',
            [
                '--filter' => 'Rbl',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Rbl', $output);
        $this->assertNotContains('Snds', $output);
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'account:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No account found for given filter.', Artisan::output());
    }
}
