<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ShowCommandTest.
 *
 * @note In this test we use the ticket factory to create tickets for the notes.
 *       - The tickets model is really large to set up so we use factories to keep the test code clean.
 */
class ShowCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testNoteShowCommandShouldFailWithNoNotes(): void
    {
        $exitCode = Artisan::call('note:show');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "note").', $output);
        $this->assertStringContainsString('Shows a note based on the provided id', $output);
    }

    #[group('functional')]
    public function testNoteShowCommandShouldPassWithValidNoteId(): void
    {
        $headers = ['Id',  'Ticket id', 'Submitter', 'Text', 'Hidden', 'Viewed'];
        $ticket = Ticket::factory()->create();
        $note = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester',
            'text' => 'This is a test note to be deleted by the unit tests.',
            'hidden' => false,
            'viewed' => false,
        ]);

        $exitCode = Artisan::call(
            'note:show',
            [
                'note' => $note->id,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $el) {
            $this->assertStringContainsString($el, $output);
        }
    }

    #[group('functional')]
    public function testNoteShowCommandShouldFailWithInvalidNoteId(): void
    {
        $exitCode = Artisan::call(
            'note:show',
            [
                'note' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No matching note was found.', Artisan::output());
    }
}
