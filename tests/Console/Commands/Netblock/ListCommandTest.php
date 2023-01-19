<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class ListCommandTest.
 */
class ListCommandTest extends TestCase
{
    public function testNetBlockListCommand()
    {
        $netblock = Netblock::all()->random();
        $contact = $netblock->contact;

        $exitCode = Artisan::call('netblock:list', []);

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($contact->name, Artisan::output());
    }

    public function testNetBlockListCommandWithValidFilter()
    {
        $netblock = Netblock::all()->random();
        $ip = $netblock->first_ip;
        $netblock_contact = $netblock->contact;
        $other_contact = Contact::where('id', '!=', $netblock_contact->id)
            ->get()->random();

        $exitCode = Artisan::call(
            'netblock:list',
            [
                '--filter' => $ip,
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString($netblock_contact->name, Artisan::output());
        $this->assertStringNotContainsString($other_contact->name, Artisan::output());
    }
}
