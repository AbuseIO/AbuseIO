<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

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
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $output = Artisan::output();
        foreach (['Name', 'Default', 'Brand', 'Id', 'Description'] as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    public function testWithValidNameFilter()
    {
        $account = Account::all()->random();

        $exitCode = Artisan::call(
            'account:show',
            [
                'account' => $account->name,
            ]
        );
        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($account->name, Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'account:show',
            [
                'account' => 'xxx',
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('No matching account was found.', Artisan::output());
    }
}
