<?php

namespace tests\Api\Netblock;

use AbuseIO\Models\Netblock;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\UpdateTestHelper;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;
    use UpdateTestHelper;

    const URL = '/api/v1/netblocks/';

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
        $netblock1 = factory(Netblock::class)->create();
        $netblock2 = factory(Netblock::class)->make()->toArray();

        $response = $this->executeCall($netblock2, $netblock1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $description = Netblock::find($netblock1->id)->description;

        $this->assertEquals(
            $description,
            $netblock2['description'],
            sprintf('netblock1->description:%s , netblock2->description:%s', $description, $netblock2['description'])
        );
    }

    public function testUpdateWithMissingProperty()
    {
        $netblock1 = factory(Netblock::class)->create();
        $netblock2 = factory(Netblock::class)->make()->toArray();

        unset($netblock2['description']);

        $response = $this->executeCall($netblock2, $netblock1->id);

        $this->assertFalse(
            $response->isSuccessful()
        );

        $this->assertStringContainsString(
            'The description field is required.',
            $response->getContent()
        );
    }
}
