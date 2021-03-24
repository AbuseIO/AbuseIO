<?php

namespace tests\Api\Domain;

use AbuseIO\Models\Domain;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\UpdateTestHelper;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    use UpdateTestHelper;

    const URL = '/api/v1/domains/';

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
        $domain1 = factory(Domain::class)->create();
        $domain2 = factory(Domain::class)->make()->toArray();

        $response = $this->executeCall($domain2, $domain1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Domain::find($domain1->id)->name,
            $domain2['name']
        );
    }

    public function testUpdateWithMissingPropertyName()
    {
        $domain1 = factory(Domain::class)->create();
        $domain2 = factory(Domain::class)->make()->toArray();

        unset($domain2['name']);

        $response = $this->executeCall($domain2, $domain1->id);

        $this->assertFalse(
            $response->isSuccessful()
        );

        $this->assertStringContainsString(
            'The name field is required.',
            $response->getContent()
        );
    }
}
