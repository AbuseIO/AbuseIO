<?php

namespace tests\Console\Commands\Account;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testHeaders()
    {
        $exitCode = Artisan::call('account:list', []);

        $this->assertEquals($exitCode, 0);

        $headers = ['Id', 'Name', 'Brand', 'Disabled'];
        $output = Artisan::output();
        foreach ($headers as $header) {
            $this->assertContains($header, $output);
        }
    }

    public function testAll()
    {
        $exitCode = Artisan::call('account:list', []);

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Account 2', $output);
        $this->assertContains('Account 3', $output);
    }

    public function testFilter()
    {
        $exitCode = Artisan::call(
            'account:list',
            [
                '--filter' => 'Account 2',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        $this->assertContains('Account 2', $output);
        $this->assertNotContains('Account 3', $output);
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
