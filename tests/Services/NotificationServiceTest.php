<?php

namespace tests\Services;

use AbuseIO\Models\Contact;
use AbuseIO\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

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
        $contact = Contact::factory()->make();

        $this->assertEquals(
            $this->service->listForContact($contact),
            []
        );
    }

    public function testListContactForAllowedActiveMethods()
    {
        $contact = Contact::factory()->create();

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
