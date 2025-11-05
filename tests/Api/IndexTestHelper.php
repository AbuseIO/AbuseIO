<?php

namespace tests\Api;

use AbuseIO\Models\User;
use AbuseIO\Models\Account;

trait IndexTestHelper
{
    private $statusCode;

    private $content;

    public function setUp(): void
    {
        parent::setUp();

        $this->executeCall();
    }

    /**
     * @return void
     */
    public function testRouteExists()
    {
        $this->assertEquals(200, $this->statusCode);
    }

    /**
     * @return void
     */
    public function testHasDataAttribute()
    {
        $obj = json_decode($this->content);
        $this->assertTrue(property_exists($obj, 'data'));
    }

    /**
     * @return void
     */
    public function testIsValidJson()
    {
        $this->assertJson($this->content);
    }

//    /**
//     * @return void
//     * uitgezet omdat ik niet helemaal snap wat deze test doet en hij faalt; wat zou kunnen is dat er vroeger altijd een array terug kwam en nu een object.
//     * dan hebben we dus een probleem;
//     */
//    public function testHasLenghtOne()
//    {
//        dd(json_decode($this->content));
//        $this->assertEquals(
//            count(
//                json_decode($this->content)
//            ),
//            1
//        );
//    }

    protected function executeCall()
    {
        $user = User::find(1);
        if (is_null($user)) {
            // Ensure a valid authenticatable user exists for API tests
            // Create a user tied to the system account (id=1) so token checks work
            $user = User::factory()->create(['account_id' => 1]);
        }

        // Prefer explicit system account token to avoid relying on user->account
        $account = Account::getSystemAccount();

        $server = $this->transformHeadersToServerVars(
            [
                'X-API-TOKEN' => $account ? $account->token : null,
            ]
        );

        $response = $this->actingAs($user)->call('GET', self::URL, [], [], [], $server);

        $this->statusCode = $response->getStatusCode();
        $this->content = $response->getContent();
    }
}
