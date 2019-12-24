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
        $account1 = factory(Account::class)->create();
        $account2 = factory(Account::class)->make();

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
