<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 *
 * @note In this test we use the ticket factory to create tickets for the notes.
 *       - The tickets model is really large to set up so we use factories to keep the test code clean.
 */
class CreateCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testCreateNoteCommandShouldFailWithNoArguments(): void
    {
        $exitCode = Artisan::call('note:create');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "ticket_id, submitter, text")', $output);
        $this->assertStringContainsString('Create a new note for a ticket in the system', $output);
    }

    #[group('functional')]
    public function testCreateNoteCommandShouldPassWithValidNote(): void
    {
        $ticket = Ticket::factory()->create();

        $exitCode = Artisan::call(
            'note:create',
            [
                'ticket_id' => $ticket->id,
                'submitter' => 'Tester',
                'text'      => 'This is a test note created by the unit tests.',
                'viewed'    => false,
                'hidden'    => 'true',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Note created successfully.', Artisan::output());
    }

    #[group('functional')]
    public function testCreateNoteCommandShouldFailWithInvalidTicketId(): void
    {
        $exitCode = Artisan::call(
            'note:create',
            [
                'ticket_id' => 500,
                'submitter' => 'Tester',
                'text'      => 'This is a test note created by the unit tests.',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Could not find the ticket.', Artisan::output());
    }
}
