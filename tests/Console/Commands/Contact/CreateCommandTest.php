<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testContactCreateCommandShouldFailWithoutArguments(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "name, reference, account_id, enabled, email, api_host")');
        Artisan::call('contact:create');
    }

    #[group('functional')]
    public function testContactCreateCommandShouldPassWithValidArguments(): void
    {
        $exitCode = Artisan::call('contact:create', [
            'name' => 'Test contact name',
            'reference' => 'Test reference',
            'account_id' => 1,
            'enabled' => true,
            'email' => 'example@mail.com',
            'api_host' => 'https://www.example.com',
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The contact has been created', Artisan::output());
    }

    #[group('integration')]
    public function testContactCreateCommandShouldPassWithApiToken(): void
    {
        Artisan::call('contact:create', [
            'name' => 'Test contact name',
            'reference' => 'Test reference',
            'account_id' => 1,
            'enabled' => true,
            'email' => 'example@mail.com',
            'api_host' => 'https://www.example.com',
            '--with_api_key' => true,
        ]);

        $contact = Contact::where('name', 'Test contact name')->first();

        $this->assertNotNull($contact->token);
        $this->assertStringContainsString('The contact has been created', Artisan::output());
    }

    #[group('functional')]
    public function testContactCreateCommandShouldFailWithInvalidAccountId(): void
    {
        $exitCode = Artisan::call('contact:create', [
            'name' => 'Test contact name',
            'reference' => 'Test reference',
            'account_id' => 10000,
            'enabled' => true,
            'email' => 'example@mail.com',
            'api_host' => 'https://www.example.com',
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('The selected account id is invalid.', Artisan::output());
    }
}
