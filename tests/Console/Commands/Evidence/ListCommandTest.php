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
        $this->assertContains('Shadowserver scan_mssql-sample Report: 2015-01-01', $output);
        $this->assertContains('USA.net Abuse Report', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'evidence:list',
            [
                '--filter' => 'Shadowserver',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Shadowserver scan_nat_pmp Report: 2015-01-01', $output);
        $this->assertNotContains('Notice of Unauthorized Use of Starz Entertainment, LLC ("Starz") Property', $output);
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
