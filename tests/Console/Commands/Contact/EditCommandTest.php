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
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Not enough arguments (missing: "id").
     */
    public function testWithoutId()
    {
        Artisan::call('contact:edit');
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
        $this->assertContains('Unable to find contact with this criteria', Artisan::output());
    }

    public function testName()
    {
        $this->assertEquals('John Doe', Contact::find(1)->name);

        $exitCode = Artisan::call(
            'contact:edit',
            [
                'id' => '1',
                '--name' => 'New name',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('The contact has been updated', Artisan::output());

        $contact = Contact::find(1);
        $this->assertEquals('New name', $contact->name);
        $contact->name = 'John Doe';
        $contact->save();
    }

    public function testCompanyName()
    {
        $this->assertEquals('JOHND', Contact::find(1)->reference);

        $exitCode = Artisan::call(
            'contact:edit',
            [
                'id' => '1',
                '--reference' => 'New reference',
            ]
        );
        $this->assertEquals($exitCode, 0);
        $this->assertContains('The contact has been updated', Artisan::output());

        $contact = Contact::find(1);
        $this->assertEquals('New reference', $contact->reference);
        $contact->reference = 'JOHND';
        $contact->save();
    }
}
