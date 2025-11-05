<?php

namespace tests\Api;

use AbuseIO\Models\Account;
use AbuseIO\Models\User;
use AbuseIO\Models\Role;

trait StoreTestHelper
{
    protected function executeCall($parameters)
    {
        $user = User::find(1);
        if (is_null($user)) {
            $user = User::factory()->create(['account_id' => 1]);
            $adminRole = Role::find(1);
            if ($adminRole) {
                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }
        }
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
