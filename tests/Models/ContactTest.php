<?php

namespace tests\Models;

use AbuseIO\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testModelFactory()
    {
        $contact = Contact::factory()->create();
        $contactFromDB = Contact::where('name', $contact->name)->first();
        $this->assertEquals($contact->name, $contactFromDB->name);
    }

    #[group('functional')]
    public function testContactNotificationMethod()
    {
        $contact = Contact::factory()->create();
        $contact->addNotificationMethod([
            'method' => 'Mail',
        ]);

        $methodsFromDB = Contact::where('name', $contact->name)->first()->notificationMethods;
        $this->assertEquals($methodsFromDB->first()->method, 'Mail');
    }

    #[group('functional')]
    public function testHasNotificationMethodWithoutMethod()
    {
        $contact = Contact::factory()->create();
        $this->assertFalse($contact->hasNotificationMethod('Mail'));
    }

    #[group('functional')]
    public function testHasNotificationMethod()
    {
        $contact = Contact::factory()->create();
        $contact->addNotificationMethod([
            'method' => 'Mail',
        ]);

        $this->assertTrue($contact->hasNotificationMethod('Mail'));
    }
}
