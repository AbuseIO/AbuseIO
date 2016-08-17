<?php

namespace tests\Api\Contact;

use AbuseIO\Models\Account;
use AbuseIO\Models\Contact;
use AbuseIO\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/api/v1/contacts/';

    public function testEmptyUpdate()
    {
        $response = $this->call([]);

        $this->assertContains(
            'ERR_WRONGARGS',
            $response->getContent()
        );

        $this->assertEquals($response->getStatusCode(), 422);
    }

    public function testUpdate()
    {
        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->make()->toArray();

        $response = $this->call($contact2, $contact1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Contact::find($contact1->id)->name,
            $contact2['name']
        );
    }

    public function testUpdateWithMissingPropertyName()
    {
        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->make()->toArray();

        unset($contact2['name']);

        $response = $this->call($contact2, $contact1->id);

        $this->assertFalse(
            $response->isSuccessful()
        );

        $this->assertContains(
            'The name field is required.',
            $response->getContent()
        );
    }

    public function call($parameters, $id = 1)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(
            [
                'Accept'      => 'application/json',
                'X-API-TOKEN' => Account::getSystemAccount()->token,
            ]);

        return parent::call('PUT', $this->getUri($id), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }
}
