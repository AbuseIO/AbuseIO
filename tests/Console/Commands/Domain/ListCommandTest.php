<?php

namespace tests\Console\Commands\Domain;

use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('domain:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Contact', 'Name', 'Enabled'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call('domain:list', []);

        $this->assertEquals($exitCode, 0);
        $this->assertContains('Customer 1', Artisan::output());
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'domain:list',
            [
                '--filter' => 'customer1.tld',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('customer1.tld', Artisan::output());
        $this->assertNotContains('johndoe.tld', Artisan::output());
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'domain:list',
            [
                '--filter' => 'domain_unknown.com',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No domain found for given filter.', Artisan::output());
    }
}
