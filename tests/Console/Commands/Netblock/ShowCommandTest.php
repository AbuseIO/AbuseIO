<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
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
    public function testNetblockShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('netblock:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "netblock").', $output);
        $this->assertStringContainsString('Shows the details of a netblock', $output);
    }

    #[group('functional')]
    public function testNetblockShowCommandShouldPassWithValidContactFilter(): void
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
            'netblock:show',
            [
                'netblock' => $netblock->contact->name,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($netblock->contact->name, Artisan::output());
    }

    #[group('functional')]
    public function testNetblockShowCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'netblock:show',
            [
                'netblock' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching netblock was found.', Artisan::output());
    }

    #[group('functional')]
    public function testNetblockShowCommandShouldPassWithStartIpFilter(): void
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
            'netblock:show',
            [
                'netblock' => $netblock->first_ip,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($netblock->contact->name, Artisan::output());
    }

    #[group('functional')]
    public function testNetBlockShowCommandShouldPassWithStartEndFilter(): void
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
            'netblock:show',
            [
                'netblock' => $netblock->last_ip,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($netblock->contact->name, Artisan::output());
    }
}
