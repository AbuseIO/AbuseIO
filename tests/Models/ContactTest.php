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
}
