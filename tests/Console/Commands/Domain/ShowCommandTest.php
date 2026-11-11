<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
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
    public function testDomainShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('domain:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "domain")', $output);
        $this->assertStringContainsString('Shows a domain based on the provided ID or name', $output);
    }

    #[group('functional')]
    public function testDomainShowCommandShouldPassWithValidNameFilter(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);

        $domain = Domain::create([
            'name' => 'example.com',
            'contact_id' => $contact->id,
            'enabled' => true,
        ]);

        $exitCode = Artisan::call('domain:show', ['domain' => $domain->name]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($domain->name, Artisan::output());
    }

    #[group('functional')]
    public function testDomainShowCommandShouldPassWithValidIdFilter(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);

        $domain = Domain::create([
            'name' => 'example.com',
            'contact_id' => $contact->id,
            'enabled' => true,
        ]);

        $exitCode = Artisan::call('domain:show', ['domain' => (string) $domain->id]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($domain->name, Artisan::output());
    }

    #[group('functional')]
    public function testDomainShowCommandShouldFailWithInvalidIdFilter(): void
    {
        $exitCode = Artisan::call(
            'domain:show',
            [
                'domain' => '1000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching domain was found.', Artisan::output());
    }

    #[group('functional')]
    public function testDomainShowCommandShouldPassWithJsonFlag(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);

        $domain = Domain::create([
            'name' => 'example.com',
            'contact_id' => $contact->id,
            'enabled' => true,
        ]);

        $exitCode = Artisan::call('domain:show', [
            'domain' => $domain->id,
            '--json' => true,
        ]);
        json_decode(Artisan::output());

        $this->assertEquals(0, $exitCode);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }
}
