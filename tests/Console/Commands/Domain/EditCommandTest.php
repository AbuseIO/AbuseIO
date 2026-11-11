<?php

namespace tests\Console\Commands\Domain;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testDomainEditCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('domain:edit');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Edits an existing domain', $output);
    }

    #[group('functional')]
    public function testDomainEditCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id' => '10000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find domain with this criteria', Artisan::output());
    }

    #[group('functional')]
    public function testDomainEditCommandShouldFailWithInvalidContact(): void
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
            'domain:edit',
            [
                'id'           => (string) $domain->id,
                '--contact_id' => '100000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find contact with this criteria', Artisan::output());
    }

    #[group('functional')]
    public function testDomainEditCommandShouldPassSettingEnabledToFalse(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);
        $domain = Domain::create([
            'name'       => 'example.com',
            'contact_id' => $contact->id,
            'enabled'    => true,
        ]);

        $exitCode = Artisan::call(
            'domain:edit',
            [
                'id'        => $domain->id,
                '--enabled' => 'false',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Domain updated successfully.', Artisan::output());
    }
}
