<?php

namespace tests\Api;

use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\Netblock;
use AbuseIO\Models\Note;
use AbuseIO\Models\Role;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\User;

trait UpdateTestHelper
{
    public function executeCall($parameters, $id = null)
    {
        $systemAccount = Account::getSystemAccount();
        $user = $systemAccount ? $systemAccount->admins()->first() : null;
        if (!$user) {
            $user = User::factory()->create(['account_id' => $systemAccount ? $systemAccount->id : 1]);
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }
        }
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X-API-TOKEN' => $systemAccount ? $systemAccount->token : null,
            ]
        );

        // Resolve ID dynamically when not provided
        $resolvedId = $id ?? $this->createResourceAndGetId();

        return $this->call('PUT', $this->getUri($resolvedId), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }

    /**
     * Create a resource for the current URL and return its id.
     */
    private function createResourceAndGetId()
    {
        $url = rtrim(self::URL, '/');
        $segments = explode('/', $url);
        $resource = end($segments);

        switch ($resource) {
            case 'contacts':
                return Contact::factory()->create()->id;
            case 'domains':
                return Domain::factory()->create()->id;
            case 'netblocks':
                return Netblock::factory()->create()->id;
            case 'tickets':
                return Ticket::factory()->create()->id;
            case 'accounts':
                return Account::factory()->create()->id;
            case 'brands':
                return Brand::factory()->create()->id;
            case 'notes':
                return Note::factory()->create()->id;
            case 'evidence':
                return Evidence::factory()->create()->id;
            case 'users':
                return User::factory()->create()->id;
            default:
                return Contact::factory()->create()->id;
        }
    }
}
