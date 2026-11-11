<?php

namespace tests\Models;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

class DomainTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testModelFactory()
    {
        $contact = Contact::factory()->create();
        $domain = Domain::factory()->create([
            'contact_id' => $contact->id,
        ]);

        $domainFromDB = Domain::where('name', $domain->name)->first();
        $this->assertEquals($domain->name, $domainFromDB->name);
    }
}
