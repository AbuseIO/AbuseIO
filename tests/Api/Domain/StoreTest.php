<?php

namespace tests\Api\Domain;

use AbuseIO\Models\Domain;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\StoreTestHelper;
use tests\TestCase;

class StoreTest extends TestCase
{
    use StoreTestHelper;
    use DatabaseTransactions;

    const URL = '/api/v1/domains';

    public function testValidationErrors()
    {
        $response = $this->executeCall([]);

        $this->assertStringContainsString(
            'The name field is required.',
            $response->getContent()
        );
    }

    public function testSuccessfulCreate()
    {
        $domain = factory(Domain::class)->make()->toArray();

        $response = $this->executeCall($domain);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $obj = $response->getContent();

        unset($domain['contact_id']);

        foreach ($domain as $key => $value) {
            $this->assertStringContainsString(
                $key,
                $obj
            );
        }
    }
}
