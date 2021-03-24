<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Contact;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    public function testWithValidIdFilter()
    {
        $exitCode = Artisan::call(
            'contact:show',
            [
                'contact' => '1',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $output = Artisan::output();
        foreach (['Reference', 'Name', 'Email', 'Api host', 'Notification methods', 'Enabled'] as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    public function testWithValidNameFilter()
    {
        $contact = Contact::all()->random();

        $exitCode = Artisan::call(
            'contact:show',
            [
                'contact' => $contact->name,
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString($contact->name, Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $exitCode = Artisan::call(
            'contact:show',
            [
                'contact' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No matching contact was found.', Artisan::output());
    }
}
