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

trait ShowTestHelper
{
    private $statusCode;

    private $content;

    /**
     * @return void
     */
    public function testStatusCodeValidRequest()
    {
        $this->initWithValidResponse();

        $this->assertEquals(200, $this->statusCode);

        $obj = json_decode($this->content);
        $this->assertTrue($obj->message->success);
    }

    /**
     * @return void
     */
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

        // Create a valid record for the current URL resource and use its id
        $id = $this->createResourceAndGetId();

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X-API-TOKEN' => $systemAccount ? $systemAccount->token : null,
            ]
        );
        $response = $this->actingAs($user)->call('GET', self::URL.'/'.(string) $id, [], [], [], $server);

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

    /**
     * @return void
     */
    public function testHasDataAttribute()
    {
        $this->initWithValidResponse();
        $obj = json_decode($this->content);
        $this->assertTrue(property_exists($obj, 'data'));
    }

    /**
     * @return void
     */
    public function testIsValidJson()
    {
        $this->initWithValidResponse();
        $this->assertJson($this->content);
    }

    /**
     * @return void
     */
    public function testStatusCodeInvalidRequest()
    {
        $this->initWithInvalidResponse();
        $this->assertEquals(404, $this->statusCode);
    }

    /**
     * @return void
     */
    public function initWithInvalidResponse()
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

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X-API-TOKEN' => $systemAccount ? $systemAccount->token : null,
            ]
        );
        $response = $this->actingAs($user)->call('GET', self::URL.'/20000', [], [], [], $server);

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }

    /**
     * Create a valid resource for the current URL and return its id.
     *
     * @return int|string
     */
    private function createResourceAndGetId()
    {
        // Extract last path segment from self::URL
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
                // Fallback: try to create a contact as a generic resource
                return Contact::factory()->create()->id;
        }
    }
}
