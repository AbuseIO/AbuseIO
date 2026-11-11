<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testContactShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('contact:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "contact")', $output);
        $this->assertStringContainsString('Shows details of a contact based on the given id or name', $output);
    }

    #[group('integration')]
    public function testContactShowCommandShouldPassWithValidIdFilter(): void
    {
        $headers = ['Id', 'Reference', 'Name', 'Email', 'Api host', 'Enabled', 'Account'];
        $contact = Contact::create([
            'reference' => sprintf('reference_%s', uniqid()),
            'name' => 'Test name no. 1',
            'email' => 'example@mail.com',
            'api_host' => 'https://api.example.com',
            'enabled' => true,
            'account_id' => 1,
        ]);

        $exitCode = Artisan::call(
            'contact:show',
            [
                'contact' => $contact->id,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    #[group('integration')]
    public function testContactShowCommandShouldPassWithValidNameFilter(): void
    {
        $contact = Contact::create([
            'reference' => sprintf('reference_%s', uniqid()),
            'name' => 'Test name no. 1',
            'email' => 'example@mail.com',
            'api_host' => 'https://api.example.com',
            'enabled' => true,
            'account_id' => 1,
        ]);

        $exitCode = Artisan::call(
            'contact:show',
            [
                'contact' => $contact->name,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($contact->name, Artisan::output());
    }

    #[group('integration')]
    public function testContactShowCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'contact:show',
            [
                'contact' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching contact was found.', Artisan::output());
    }
}
