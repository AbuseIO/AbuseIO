<?php

namespace tests\Api\Contact;

use AbuseIO\Models\Contact;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\UpdateTestHelper;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    use UpdateTestHelper;

    const URL = '/api/v1/contacts/';

    public function testEmptyUpdate()
    {
        $response = $this->executeCall([]);

        $this->assertStringContainsString(
            'ERR_WRONGARGS',
            $response->getContent()
        );

        $this->assertEquals($response->getStatusCode(), 422);
    }

    public function testUpdate()
    {
        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->make()->toArray();

        $response = $this->executeCall($contact2, $contact1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Contact::find($contact1->id)->name,
            $contact2['name']
        );
    }

    public function testUpdateWithMissingPropertyName()
    {
        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->make()->toArray();

        unset($contact2['name']);

        $response = $this->executeCall($contact2, $contact1->id);

        $this->assertFalse(
            $response->isSuccessful()
        );

        $this->assertStringContainsString(
            'The name field is required.',
            $response->getContent()
        );
    }
}
