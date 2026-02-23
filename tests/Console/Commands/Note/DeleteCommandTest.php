<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class DeleteCommandTest.
 *
 * @note In this test we use the ticket factory to create tickets for the notes.
 *       - The tickets model is really large to set up so we use factories to keep the test code clean.
 */
class DeleteCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testNoteDeleteCommandShouldFailWithNoArguments(): void
    {
        $exitCode = Artisan::call('note:delete');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Deletes a note from the system', $output);
    }

    #[group('functional')]
    public function testNoteDeleteCommandShouldPassWithValidId(): void
    {
        $ticket = Ticket::factory()->create();
        $note = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester',
            'text'      => 'This is a test note to be deleted by the unit tests.',
            'hidden'    => false,
            'viewed'    => false,
        ]);

        $exitCode = Artisan::call('note:delete', [
            'id' => $note->id,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString("Successfully deleted the note.", Artisan::output());
    }

    #[group('functional')]
    public function testNoteDeleteCommandShouldFailWithInvalidId(): void
    {
        $exitCode = Artisan::call(
            'note:delete',
            [
                'id' => '1000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find note', Artisan::output());
    }
}
