<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Domain;
use AbuseIO\Models\Contact;
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
    public function testDomainDeleteCommandShouldFailWithoutId(): void
    {
        $exitCode = Artisan::call('domain:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id").', $output);
        $this->assertStringContainsString('Deletes a domain from the system', $output);
    }

    #[group('functional')]
    public function testDomainDeleteCommandShouldPassWithValidDomain(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference' => 'Old reference',
            'name' => 'Old name',
            'email' => 'test@example.com',
            'enabled' => true,
        ]);
        $domain = Domain::create([
            'name' => 'example.com',
            'contact_id' => $contact->id,
            'enabled' => true,
        ]);

        $exitCode = Artisan::call('domain:delete', ['id' => (string)$domain->id]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Domain has been deleted', Artisan::output());

    }

    #[group('functional')]
    public function testDomainDeleteCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'domain:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find domain.', Artisan::output());
    }
}
