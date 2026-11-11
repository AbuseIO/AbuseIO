<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
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
    public function testNetblockEditCommandShouldFailWithoutId(): void
    {
        $exitCode = Artisan::call('netblock:edit');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Edits an existing netblock', $output);
    }

    #[group('functional')]
    public function testNetblockEditCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id' => '10000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find netblock with this criteria', Artisan::output());
    }

    #[group('functional')]
    public function testNetblockEditCommandShouldFailWithInvalidContact(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);
        $netblock = Netblock::create([
            'contact_id'  => $contact->id,
            'first_ip'    => '192.168.1.1',
            'last_ip'     => '192.168.1.10',
            'description' => 'Test netblock',
            'enabled'     => true,
        ]);

        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id'           => (string) $netblock->id,
                '--contact_id' => '100000', // invalid contact id
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find contact with this criteria', Artisan::output());
    }

    #[group('functional')]
    public function testNetblockEditCommandShouldPassSettingEnabledToFalse(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);
        $netblock = Netblock::create([
            'contact_id'  => $contact->id,
            'first_ip'    => '192.168.1.1',
            'last_ip'     => '192.168.1.10',
            'description' => 'Test netblock',
            'enabled'     => true,
        ]);

        $exitCode = Artisan::call(
            'netblock:edit',
            [
                'id'        => $netblock->id,
                '--enabled' => 'false',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('The netblock has been updated', Artisan::output());
    }
}
