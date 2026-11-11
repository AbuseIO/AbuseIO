<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 *
 * @note This test class assumes that there is at least one account with ID 1 in the database.
 */
class ShowCommandTest extends TestCase
{
    #[group('functional')]
    public function testAccountShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('account:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "account")', $output);
        $this->assertStringContainsString('Shows an account', $output);
    }

    #[group('functional')]
    public function testAccountShowCommandWithValidIdFilter(): void
    {
        $headers = ['Name', 'Default', 'Brand', 'Id', 'Description'];

        $exitCode = Artisan::call(
            'account:show',
            [
                'account' => '1',
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    #[group('functional')]
    public function testAccountShowCommandShouldPassWithValidNameFilter(): void
    {
        $account = Account::all()->random();

        $exitCode = Artisan::call(
            'account:show',
            [
                'account' => $account->name,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($account->name, Artisan::output());
    }

    #[group('functional')]
    public function testAccountShowCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'account:show',
            [
                'account' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching account was found.', Artisan::output());
    }
}
