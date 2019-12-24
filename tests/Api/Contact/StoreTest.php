<?php

namespace tests\Api\Contact;

use AbuseIO\Models\Contact;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\StoreTestHelper;
use tests\TestCase;

class StoreTest extends TestCase
{
    use StoreTestHelper;
    use DatabaseTransactions;

    const URL = '/api/v1/contacts';

    public function testValidationErrors()
    {
        $response = $this->executeCall([]);

        $this->assertStringContainsString(
            'The name field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $contact = factory(Contact::class)->make()->toArray();

        $response = $this->executeCall($contact);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $obj = $response->getContent();

        unset($contact['account_id']);

        foreach ($contact as $value) {
            if ($value && $value != 1) {
                $value = str_replace('/', '\/', $value);
                $this->assertStringContainsString(
                    $value,
                    $obj
                );
            }
        }
    }
}
