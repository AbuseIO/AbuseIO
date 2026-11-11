<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testAccountListCommandTableHeaders(): void
    {
        $headers = ['Id', 'Name', 'Brand', 'Disabled'];

        $exitCode = Artisan::call('account:list', []);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testAccountListCommandShouldPassWithCreatedAccountsInTable(): void
    {
        $brand = new Brand()::create([
            'name' => 'Functional Test Brand',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);
        $accountOne = new Account()::create(
            [
                'name' => 'test name no. 1',
                'description' => 'test description',
                'disabled' => 0,
                'token' => generateApiToken(),
                'systemaccount' => 0,
                'brand_id' => $brand->id,
            ]
        );
        $accountTwo = new Account()::create(
            [
                'name' => 'test name no. 2',
                'description' => 'test description',
                'disabled' => 0,
                'token' => generateApiToken(),
                'systemaccount' => 0,
                'brand_id' => $brand->id,
            ]
        );

        $exitCode = Artisan::call('account:list', []);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($accountOne->name, $output);
        $this->assertStringContainsString($accountTwo->name, $output);
    }

    #[group('functional')]
    public function testAccountListCommandShouldPassWithValidFilter(): void
    {
        $brand = new Brand()::create([
            'name' => 'Functional Test Brand',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);
        $account = new Account()::create(
            [
                'name' => 'test name no. 1',
                'description' => 'test description',
                'disabled' => 0,
                'token' => generateApiToken(),
                'systemaccount' => 0,
                'brand_id' => $brand->id,
            ]
        );
        $account2 = new Account()::create(
            [
                'name' => 'test name no. 2',
                'description' => 'test description',
                'disabled' => 0,
                'token' => generateApiToken(),
                'systemaccount' => 0,
                'brand_id' => $brand->id,
            ]
        );

        $exitCode = Artisan::call(
            'account:list',
            [
                '--filter' => $account->name,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($account->name, $output);
        $this->assertStringNotContainsString($account2->name, $output);
    }

    #[group('functional')]
    public function testAccountListCommandShouldPassWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'account:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No accounts found.', Artisan::output());
    }
}
