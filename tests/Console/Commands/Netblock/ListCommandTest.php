<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

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

        $this->assertEquals($exitCode, 0);
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

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString($netblock_contact->name, Artisan::output());
        $this->assertStringNotContainsString($other_contact->name, Artisan::output());
    }
}
