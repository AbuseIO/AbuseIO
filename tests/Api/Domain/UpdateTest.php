<?php

namespace tests\Api\Contact;

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
            'reference',
            $response->getContent()
        );

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testUpdateName()
    {
        $contact1 = factory(Contact::class)->create();
        $contact2 = factory(Contact::class)->make();

        $response = $this->call(['name' => $contact2->name], $contact1->id);

        $this->assertTrue(
            $response->isSuccessful()
        );

        $this->assertEquals(
            Contact::find($contact1->id)->name,
            $contact2->name
        );
    }

    public function call($parameters, $id = 1)
    {
        $user = User::find(1);
        $this->actingAs($user);

        $server = $this->transformHeadersToServerVars(['Accept' => 'application/json']);

        return parent::call('PUT', $this->getUri($id), $parameters, [], [], $server);
    }

    private function getUri($id)
    {
        return self::URL.$id;
    }
}
