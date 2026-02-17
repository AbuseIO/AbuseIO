<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Contact;
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
    public function testContactEditCommandShouldFailWithoutId(): void
    {
        $exitCode = Artisan::call('contact:edit');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id").', $output);
        $this->assertStringContainsString('Edit an existing contact', $output);

    }

    #[group('functional')]
    public function testContactEditCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'contact:edit',
            [
                'id' => '10000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find contact.', Artisan::output());
    }

    #[group('integration')]
    public function testContactEditCommandShouldPassEditingTheName(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);

        $exitCode = Artisan::call(
            'contact:edit',
            [
                'id'     => $contact->id,
                '--name' => 'New name',
            ]
        );
        $contact = Contact::find($contact->id);

        $this->assertEquals(0, $exitCode);
        $this->assertEquals('New name', $contact->name);
        $this->assertStringContainsString('The contact has been updated', Artisan::output());
    }

    #[group('integration')]
    public function testContactEditCommandShouldPassEditingTheCompanyName(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);

        $exitCode = Artisan::call(
            'contact:edit',
            [
                'id'          => $contact->id,
                '--reference' => 'New reference',
            ]
        );
        $contact = Contact::find($contact->id);

        $this->assertEquals(0, $exitCode);
        $this->assertEquals('New reference', $contact->reference);
        $this->assertStringContainsString('The contact has been updated', Artisan::output());
    }
}
