<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Domain;
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
    public function testDomainListCommandShouldPassShowingTheHeaders(): void
    {
        $headers = ['Id', 'Contact', 'Name', 'Enabled'];
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);

        Domain::create([
            'name' => 'example.com',
            'contact_id' => $contact->id,
            'enabled' => true,
        ]);

        $exitCode = Artisan::call('domain:list', []);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);

        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testDomainListCommandShouldPassWithNoFilter(): void
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

        $contact = $domain->contact;

        $exitCode = Artisan::call('domain:list', []);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($contact->name, Artisan::output());
    }

    #[group('functional')]
    public function testDomainListCommandShouldPassWithValidFilter(): void
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

        $exitCode = Artisan::call(
            'domain:list',
            [
                '--filter' => $domain->name,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($domain->name, Artisan::output());
        $this->assertStringNotContainsString('johndoe.tld', Artisan::output());
    }


    #[group('integration')]
    public function testDomainListCommandShouldFailNotFoundFilter()
    {
        $exitCode = Artisan::call(
            'domain:list',
            [
                '--filter' => 'domain_unknown.com',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No domain found for given filter.', Artisan::output());
    }
}
