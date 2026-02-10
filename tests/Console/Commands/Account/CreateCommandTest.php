<?php

namespace tests\Console\Commands\Account;

use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Console\Exception\RuntimeException;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testAccountCreateCommandShouldFailWithoutArguments(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "name, brand_id")');

        Artisan::call('account:create');
    }

    #[group('integration')]
    public function testAccountCreateCommandShouldPassWithValidArguments(): void
    {
        $brand = new Brand()::create([
            'name' => 'Functional Test Brand',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);

        $exitCode = Artisan::call('account:create', [
            'name'     => 'test account',
            'brand_id' => $brand->id,
        ]);
        $account = Account::where('name', 'test account')->first();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The account has been created', Artisan::output());
        $this->assertEquals('test account', $account->name);
    }

    #[group('integration')]
    public function testCreatingAnAccountShouldPassWithValidApiKeyValue(): void
    {
        $brand = new Brand()::create([
            'name' => 'Functional Test Brand',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);

        $exitCode = Artisan::call('account:create', [
            'name'     => 'test account',
            'brand_id' => $brand->id,
            '--with_api_key' => true,
        ]);
        $account = Account::where('name', 'test account')->first();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The account has been created', Artisan::output());
        $this->assertNotNull($account->token);
    }

    #[group('integration')]
    public function testCreatingAnAccountShouldPassWithApiKeyAttributeOnFalse(): void
    {
        $brand = new Brand()::create([
            'name' => 'Functional Test Brand',
            'company_name' => 'Testing Co',
            'logo' => 'logo.png',
            'introduction_text' => 'Welcome to Testing Co',
            'creator_id' => 1,
        ]);

        $exitCode = Artisan::call('account:create', [
            'name'     => 'test account',
            'brand_id' => $brand->id,
            '--with_api_key' => false,
        ]);

        $account = Account::where('name', 'test account')->first();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The account has been created', Artisan::output());
        $this->assertNull($account->token);
    }
}
