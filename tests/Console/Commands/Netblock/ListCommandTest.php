<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
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
    public function testNetBlockListCommandShouldFailWithNoNetblocks(): void
    {
        $exitCode = Artisan::call('netblock:list', []);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No netblocks found.', Artisan::output());
    }

    #[group('functional')]
    public function testNetBlockListCommandShouldPassWithAValidNetblock(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference' => 'Old reference',
            'name' => 'Old name',
            'email' => 'test@example.com',
            'enabled' => true,
        ]);

        $netblock = Netblock::create([
            'contact_id' => $contact->id,
            'first_ip' => '192.168.1.1',
            'last_ip' => '192.168.1.10',
            'description' => 'Test netblock',
            'enabled' => true,
        ]);

        $exitCode = Artisan::call('netblock:list', []);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($netblock->contact->name, Artisan::output());
    }

    #[group('functional')]
    public function testNetBlockListCommandShouldPassWithValidFilter(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference' => 'Old reference',
            'name' => 'Old name',
            'email' => 'test@example.com',
            'enabled' => true,
        ]);
        $otherContact = Contact::create([
            'account_id' => 1,
            'reference' => 'Other reference',
            'name' => 'Other name',
            'email' => 'othertTest@example.com',
            'enabled' => true,
        ]);
        $netblock = Netblock::create([
            'contact_id' => $contact->id,
            'first_ip' => '192.168.1.1',
            'last_ip' => '192.168.1.10',
            'description' => 'Test netblock',
            'enabled' => true,
        ]);
        $netblockContact = $netblock->contact;

        $exitCode = Artisan::call(
            'netblock:list',
            [
                '--filter' => $netblock->first_ip,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($netblockContact->name, $output);
        $this->assertStringNotContainsString($otherContact->name, $output);
    }
}
