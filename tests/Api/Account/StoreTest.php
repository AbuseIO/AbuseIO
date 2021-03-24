<?php

namespace tests\Api\Account;

use AbuseIO\Models\Account;
use tests\Api\StoreTestHelper;
use tests\TestCase;

class StoreTest extends TestCase
{
    use StoreTestHelper;

    const URL = '/api/v1/accounts';

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
        $account = factory(Account::class)->make()->toArray();

        $response = $this->executeCall($account);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $obj = json_decode($response->getContent());

        $this->assertTrue($account['name'] == $obj->data->name);
    }
}
