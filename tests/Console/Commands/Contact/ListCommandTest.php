<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Contact;
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
    public function testContactListCommandShouldPassShowingContacts(): void
    {
        $contactOne = Contact::create([
            'reference' => sprintf('reference_%s', uniqid()),
            'name' => 'Test name no. 1',
            'email' => 'example@mail.com',
            'api_host' => 'https://api.example.com',
            'enabled' => true,
            'account_id' => 1,
        ]);
        $contactTwo = Contact::create([
            'reference' => sprintf('reference_%s', uniqid()),
            'name' => 'Test name no. 2',
            'email' => 'example@mail.com',
            'api_host' => 'https://api.example.com',
            'enabled' => true,
            'account_id' => 1,
        ]);

        $exitCode = Artisan::call('contact:list');
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($contactOne->name, $output);
        $this->assertStringContainsString($contactTwo->name, $output);
    }

    #[group('integration')]
    public function testContactListCommandShouldPassWithValidNameFilter(): void
    {
        $contactOne = Contact::create([
            'reference' => sprintf('reference_%s', uniqid()),
            'name' => 'Test name no. 1',
            'email' => 'example@mail.com',
            'api_host' => 'https://api.example.com',
            'enabled' => true,
            'account_id' => 1,
        ]);
        $contactTwo = Contact::create([
            'reference' => sprintf('reference_%s', uniqid()),
            'name' => 'Test name no. 2',
            'email' => 'example@mail.com',
            'api_host' => 'https://api.example.com',
            'enabled' => true,
            'account_id' => 1,
        ]);

        $exitCode = Artisan::call(
            'contact:list',
            [
                '--filter' => $contactOne->name,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($contactOne->name, $output);
        $this->assertStringNotContainsString($contactTwo->name, $output);
    }

    #[group('integration')]
    public function testContactListCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'contact:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No contacts found.', Artisan::output());
    }
}
