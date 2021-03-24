<?php

namespace tests\Console\Commands\Netblock;

use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 */
class ShowCommandTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Netblock */
    private $netblock;

    private function initDB()
    {
        $this->netblock = factory(Netblock::class)->create(
            ['contact_id' => factory(Contact::class)->create()->id]
        );
    }

    public function testWithValidContactFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'netblock:show',
            [
                'netblock' => $this->netblock->contact->name,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString($this->netblock->contact->name, Artisan::output());
    }

    public function testWithInvalidFilter()
    {
        $this->initDB();
        $exitCode = Artisan::call(
            'netblock:show',
            [
                'netblock' => 'xxx',
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString('No matching netblock was found.', Artisan::output());
    }

    public function testWithStartIpFilter()
    {
        $this->initDB();
        $ip = $this->netblock->first_ip;

        $exitCode = Artisan::call(
            'netblock:show',
            [
                'netblock' => $ip,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString($this->netblock->contact->name, Artisan::output());
    }

    public function testNetBlockShowWithStartEndFilter()
    {
        $this->initDB();
        $ip = $this->netblock->last_ip;

        $exitCode = Artisan::call(
            'netblock:show',
            [
                'netblock' => $ip,
            ]
        );

        $this->assertEquals($exitCode, 0);
        $this->assertStringContainsString($this->netblock->contact->name, Artisan::output());
    }
}
