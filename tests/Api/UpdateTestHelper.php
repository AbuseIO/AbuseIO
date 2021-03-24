<?php

namespace tests\Api;

use AbuseIO\Models\Account;
use AbuseIO\Models\User;

trait UpdateTestHelper
{
    public function executeCall($parameters, $id = 1)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X-API-TOKEN' => Account::getSystemAccount()->token,
            ]
        );

        return $this->call('PUT', $this->getUri($id), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }
}
