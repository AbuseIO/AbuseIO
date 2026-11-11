<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
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
    public function testNetblockDeleteCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('netblock:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id").', $output);
        $this->assertStringContainsString('Deletes a netblock from the system', $output);
    }

    #[group('functional')]
    public function testNetblockDeleteCommandShouldPassWithValidNetblock(): void
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
            'netblock:delete',
            [
                'id' => $netblock->id,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Netblock has been deleted.', Artisan::output());
    }

    #[group('functional')]
    public function testNetblockDeleteCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'netblock:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find netblock.', Artisan::output());
    }
}
