<?php

namespace tests\Console\Commands\Contact;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('contact:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Name', 'Email', 'Api host'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call('contact:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Customer 2', $output);
        $this->assertContains('Customer 3', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'contact:list',
            [
                '--filter' => 'Customer 2',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Customer 2', $output);
        $this->assertNotContains('Customer 3', $output);
    }

    public function testNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'contact:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No contact found for given filter.', Artisan::output());
    }
}
