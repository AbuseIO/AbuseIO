<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 */
class DeleteCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testDeletingAnAccountShouldFailWithoutId(): void
    {
        $exitCode = Artisan::call('account:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Deletes an account', $output);
    }

    #[group('functional')]
    public function testAccountDeleteCommandShouldPassWithValidId(): void
    {
        $brand = Brand::create([
            'name' => 'Functional Test Brand',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);

        $account = Account::create([
            'name'          => 'test name',
            'description'   => 'test description',
            'disabled'      => 0,
            'token'         => generateApiToken(),
            'systemaccount' => 0,
            'brand_id'      => $brand->id,
        ]);

        $exitCode = Artisan::call('account:delete', [
            'id' => $account->id,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString("Account deleted successfully.", Artisan::output());
    }

    #[group('functional')]
    public function testAccountDeleteCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'account:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Account not found.', Artisan::output());
    }
}
