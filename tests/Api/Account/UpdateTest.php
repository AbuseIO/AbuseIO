<?php

namespace tests\Api\Account;

use AbuseIO\Models\Account;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\UpdateTestHelper;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    use UpdateTestHelper;

    const URL = '/api/v1/accounts/';

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
        $account1 = Account::factory()->create();
        $account2 = Account::factory()->make();

        $response = $this->executeCall(['name' => $account2->name, 'brand_id' => $account1->brand_id], $account1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Account::find($account1->id)->name,
            $account2->name
        );
    }
}
