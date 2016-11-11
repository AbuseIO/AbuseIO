<?php

namespace tests\Api\Note;

use AbuseIO\Models\Note as Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\Api\StoreTestHelper;
use tests\TestCase;

class StoreTest extends TestCase
{
    use StoreTestHelper;
    use DatabaseTransactions;

    const URL = '/api/v1/notes';

    public function testValidationErrors()
    {
        $response = $this->executeCall([]);

        $this->assertContains(
            'The text field is required.',
            $response->getContent()
        );
    }

    public function testSuccesfullCreate()
    {
        $note = factory(Model::class)->make()->toArray();

//        $mailer = $this->getMock('Swift_Mailer', array('send'), array(new \Swift_NullTransport()));
//
//        $this->app['mailer']->setSwiftMailer($mailer);
//
//        $mailer->expects($this->once())
//            ->method('send');
        global $testrunner;
        $testrunner= true;

        $response = $this->executeCall($note);


        $this->assertTrue(
            $response->isSuccessful()
        );

        $obj = $response->getContent();

        foreach ($note as $key => $value) {
            $this->assertContains(
                $key,
                $obj
            );
        }
    }
}
