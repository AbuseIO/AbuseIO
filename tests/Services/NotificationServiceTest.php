<?php

namespace tests\Services;

use AbuseIO\Models\Contact;
use AbuseIO\Services\NotificationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $service;

    public function testListAll()
    {
        $this->assertEquals(
            $this->service->listAll(),
            ['Mail']
        );
    }

    public function testListContact()
    {
        $contact = factory(Contact::class)->create();

        $this->assertEquals(
            $this->service->listForContact($contact),
            []
        );
    }

    public function testListContactForAllowedActiveMethods()
    {
        $contact = factory(Contact::class)->create();

        $contact->addNotificationMethod([
            'method' => 'Mail',
        ]);

        $contact->addNotificationMethod([
            'method' => 'SomeBogusNotificationMethod',
        ]);

        $this->assertEquals(
            $this->service->listForContact($contact),
            ['Mail']
        );
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new NotificationService();
    }
}
