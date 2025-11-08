<?php

namespace tests\Api\Netblock;

use AbuseIO\Models\Account;
use AbuseIO\Models\Role;
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
        // Resolve an admin user under the system account, create if necessary
        $systemAccount = Account::getSystemAccount();
        $user = $systemAccount ? $systemAccount->admins()->first() : null;
        if (!$user) {
            $user = User::factory()->create(['account_id' => $systemAccount ? $systemAccount->id : 1]);
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }
        }

        $netblock = Netblock::factory()->create();

        $server = $this->transformHeadersToServerVars(
            [
                'X-API-TOKEN' => Account::getSystemAccount()->token,
            ]
        );

        $response = $this->actingAs($user)->call('DELETE', self::getURLWithId($netblock->id), [], [], [], $server);

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

    private static function getURLWithId($id)
    {
        return sprintf('%s/%s', self::URL, $id);
    }
}
