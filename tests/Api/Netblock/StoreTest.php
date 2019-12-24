<?php

namespace tests\Api\Netblock;

use AbuseIO\Models\Netblock;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\StoreTestHelper;
use tests\TestCase;

class StoreTest extends TestCase
{
    use StoreTestHelper;
    use DatabaseTransactions;

    const URL = '/api/v1/netblocks';

    public function testValidationErrors()
    {
        $response = $this->executeCall([]);

        $this->assertStringContainsString(
            'The first ip field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $netblock = factory(Netblock::class)->make()->toArray();

        $response = $this->executeCall($netblock);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $obj = $response->getContent();

        unset($netblock['contact_id']);
        unset($netblock['first_ip_int']);
        unset($netblock['last_ip_int']);

        foreach ($netblock as $key => $value) {
            $this->assertStringContainsString(
                $key,
                $obj
            );
        }
    }
}
