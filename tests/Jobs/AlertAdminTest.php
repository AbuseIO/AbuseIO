<?php

namespace tests\Jobs;

use AbuseIO\Jobs\AlertAdmin;
use Log;
use Mail;
use Mockery;
use tests\TestCase;

class AlertAdminTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->alertAdmin = new AlertAdmin();
    }

    /** @test */
    public function the_attachment_format_was_set_incorrectly()
    {
        config(['app.attachment_format' => 'yellow']);

        Log::shouldReceive('error')
            ->once()
            ->with(Mockery::on(function ($msg) {
                return stripos($msg, 'Unknown attachment_format') !== false;
            }));

        Log::shouldReceive('error')
            ->once()
            ->with(Mockery::on(function ($msg) {
                return stripos($msg, 'Unable to send out alert to admin') !== false;
            }));

        $this->alertAdmin->send('There was a parsing error');
    }

    /** @test */
    public function the_attachment_format_was_set_to_multifile()
    {
        config(['app.attachment_format' => 'multifile']);

        Mail::shouldReceive('raw')->once();

        $this->alertAdmin->send('There was a parsing error');
    }

    /** @test */
    public function the_attachment_format_was_set_to_zipfile()
    {
        config(['app.attachment_format' => 'zipfile']);

        Mail::shouldReceive('raw')->once();

        $this->alertAdmin->send('There was a parsing error');
    }
}
