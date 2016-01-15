<?php

namespace tests\Console\Commands\Brand;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('brand:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Name', 'Company name'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call('brand:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('AbuseIO', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'brand:list',
            [
                '--filter' => 'default',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('AbuseIO', $output);
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'brand:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No brand found for given filter.', Artisan::output());
    }
}
