<?php

namespace tests\Api\Neblock;

use AbuseIO\Models\Netblock;
use AbuseIO\Models\User;
use tests\Api\DestroyTestHelper;
use tests\TestCase;

class DestroyTest extends TestCase
{
    use DestroyTestHelper;

    const URL = '/api/v1/netblocks';

    public function initWithValidResponse()
    {
        $user = User::find(1);

        $netblock = factory(Netblock::class)->create();

        $response = $this->actingAs($user)->call('DELETE', self::getURLWithId($netblock->id));

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

    private static function getURLWithId($id)
    {
        return sprintf('%s/%s', self::URL, $id);
    }
}
