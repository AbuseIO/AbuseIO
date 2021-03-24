<?php

namespace tests\Api;

use AbuseIO\Models\Account;
use AbuseIO\Models\User;

trait StoreTestHelper
{
    protected function executeCall($parameters)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X-API-TOKEN' => Account::getSystemAccount()->token,
            ]
        );

        return $this->call('POST', self::URL, $parameters, [], [], $server);
    }
}
