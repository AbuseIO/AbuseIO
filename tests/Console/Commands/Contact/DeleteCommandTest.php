<?php

namespace tests\Console\Commands\Contact;

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
    public function testContactDeleteCommandShouldFailWithoutIdArgument(): void
    {
        $exitCode = Artisan::call('contact:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id").', $output);
        $this->assertStringContainsString('Deletes a contact', $output);
    }

    #[group('functional')]
    public function testContactDeleteCommandShouldPassWithValidId(): void
    {
        $contact = Contact::create([
            'name' => 'Test contact name',
            'reference' => 'Test reference',
            'account_id' => 1,
            'enabled' => true,
            'email' => 'example@mail.com',
            'api_host' => 'https://www.example.com',
            '--with_api_key' => true,
        ]);

        $exitCode = Artisan::call('contact:delete', [
            'id' => $contact->id,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString("The contact has been deleted from the system", Artisan::output());
    }

    #[group('functional')]
    public function testContactDeleteCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'contact:delete',
            [
                'id' => 1000,
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find contact', Artisan::output());
    }
}
