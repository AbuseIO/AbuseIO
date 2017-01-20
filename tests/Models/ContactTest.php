<?php

namespace tests\Models;

use AbuseIO\Models\Contact;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class ContactTest extends TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        $contact = factory(Contact::class)->create();
        $contactFromDB = Contact::where('name', $contact->name)->first();
        $this->assertEquals($contact->name, $contactFromDB->name);
    }

    public function testContactNotificationMethod()
    {
        $contact = factory(Contact::class)->create();
        $contact->addNotificationMethod([
            'method' => 'Mail',
        ]);

        $methodsFromDB = Contact::where('name', $contact->name)->first()->notificationMethods;
        $this->assertEquals($methodsFromDB->first()->method, 'Mail');
    }

    public function testHasNotificationMethodWithoutMethod()
    {
        $contact = factory(Contact::class)->create();
        $this->assertFalse($contact->hasNotificationMethod('Mail'));
    }

    public function testHasNotificationMethod()
    {
        $contact = factory(Contact::class)->create();
        $contact->addNotificationMethod([
            'method' => 'Mail',
        ]);

        $this->assertTrue($contact->hasNotificationMethod('Mail'));
    }
}
