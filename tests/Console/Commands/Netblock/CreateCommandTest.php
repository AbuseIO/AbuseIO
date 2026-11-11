<?php

namespace tests\Console\Commands\Netblock;

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
    public function testNetblockCreateCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('netblock:create');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "contact, first_ip, last_ip, description")', $output);
        $this->assertStringContainsString('Creates a new netblock', $output);
    }

    #[group('functional')]
    public function testNetblockCreateCommandShouldPassWithValidNetblock(): void
    {
        $contact = Contact::create([
            'account_id' => 1,
            'reference'  => 'Old reference',
            'name'       => 'Old name',
            'email'      => 'test@example.com',
            'enabled'    => true,
        ]);

        $exitCode = Artisan::call(
            'netblock:create',
            [
                'first_ip'    => '192.168.1.1',
                'last_ip'     => '192.168.1.10',
                'description' => 'Test netblock',
                'contact'  => $contact->id,
                'enabled'     => true,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Netblock created successfully.', Artisan::output());
    }

    #[group('functional')]
    public function testCreateNetblockCommandShouldFailWithInvalidUser(): void
    {
        $exitCode = Artisan::call(
            'netblock:create',
            [
                'first_ip'    => '192.168.1.1',
                'last_ip'     => '192.168.1.10',
                'description' => 'Test netblock',
                'contact'  => 66666,
                'enabled'     => true,
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Could not find contact', Artisan::output());
    }
}
