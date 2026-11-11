<?php

namespace tests\Console\Commands\Event;

use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 *
 */
class ShowCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testEventShowCommandShouldFailWithoutArguments(): void
    {
        $exitCode = Artisan::call('event:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "event").', $output);
        $this->assertStringContainsString('Shows an event', $output);
    }

    #[group('functional')]
    public function testEventShowCommandShouldPassWithValidEvent(): void
    {
        $ticket = Ticket::factory()->create();
        $evidence = Evidence::create([
            'filename' => 'testfile.txt',
            'sender' => 'Test User',
            'subject' => 'Test Subject',
        ]);
        $event = Event::create([
            'ticket_id' => $ticket->id,
            'evidence_id' => $evidence->id,
            'source' => 'test source',
            'timestamp' => now(),
            'information' => 'Test information',
        ]);

        $exitCode = Artisan::call('event:show', [
            'event' => $event->id
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($event->information, Artisan::output());
    }

    #[group('functional')]
    public function testEventShowCommandShouldFailWithInvalidEvent(): void
    {
        $exitCode = Artisan::call('event:show', [
            'event' => '9999'
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching event was found.', Artisan::output());
    }
}
