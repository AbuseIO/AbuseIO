<?php

namespace tests\Console\Commands\Account;

use Illuminate\Support\Facades\Artisan;
use TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call(
            'account:show',
            [
                'account' => '1',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Name', 'Default', 'Brand', 'Id', 'Description'] as $el) {
            $this->assertContains($el, $output);
        }
    }

    public function testWithValidNameFilter()
    {
        $exitCode = Artisan::call(
            'account:show',
            [
                'account' => 'Customer Internet',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('Customer Internet', Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'account:show',
            [
                'account' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertContains('No matching account was found.', Artisan::output());
    }
}
