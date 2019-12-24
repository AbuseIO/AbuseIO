<?php

namespace tests\Console\Commands\Contact;

use AbuseIO\Models\Contact;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class EditCommandTest.
 */
class EditCommandTest extends TestCase
{
    public function testWithoutId()
    {
        ob_start();
        Artisan::call('contact:edit');
        $output = ob_get_clean();
        $this->assertStringContainsString('Edit a contact', $output);
    }

    public function testWithInvalidId()
    {
        $exitCode = Artisan::call(
            'contact:edit',
            [
                'id' => '10000',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('Unable to find contact with this criteria', Artisan::output());
    }

    public function testName()
    {
        $contact = Contact::all()->random();
        $oldname = $contact->name;

        $exitCode = Artisan::call(
            'contact:edit',
            [
                'id'     => $contact->id,
                '--name' => 'New name',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The contact has been updated', Artisan::output());

        // update contact
        $contact = Contact::find($contact->id);
        $this->assertEquals('New name', $contact->name);
        $contact->name = $oldname;
        $contact->save();
    }

    public function testCompanyName()
    {
        $contact = Contact::all()->random();
        $oldref = $contact->reference;

        $exitCode = Artisan::call(
            'contact:edit',
            [
                'id'          => $contact->id,
                '--reference' => 'New reference',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('The contact has been updated', Artisan::output());

        // update contact
        $contact = Contact::find($contact->id);
        $this->assertEquals('New reference', $contact->reference);
        $contact->reference = $oldref;
        $contact->save();
    }
}
