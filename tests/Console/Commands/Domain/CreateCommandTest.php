<?php

namespace tests\Console\Commands\Domain;

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
    public function testDomainCreateCommandShouldFailWithoutArguments(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "name, contact_id")');
        Artisan::call('domain:create');
    }

    #[group('functional')]
    public function testDomainCreateCommandShouldPassWithValidArguments(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);

        $exitCode = Artisan::call('domain:create', [
            'name'       => 'test.com',
            'contact_id' => $contact->id,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The domain has been created', Artisan::output());
    }

    #[group('functional')]
    public function testDomainCreateCommandShouldFailWithInvalidContactId(): void
    {
        $exitCode = Artisan::call('domain:create', [
            'name'       => 'test.com',
            'contact_id' => 9999,
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Could not find contact.', Artisan::output());
    }
}
