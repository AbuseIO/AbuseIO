<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class EditCommandTest.
 *
 * @note In this test we use the ticket factory to create tickets for the notes.
 *       - The tickets model is really large to set up so we use factories to keep the test code clean.
 */
class EditCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testNoteEditCommandShouldFailWithoutAnId()
    {
        $exitCode = Artisan::call('note:edit');
        $output = Artisan::output();

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Not enough arguments (missing: "id")', $output);
        $this->assertStringContainsString('Edits an existing note', $output);
    }

    #[group('functional')]
    public function testNoteEditCommandShouldFailWithInvalidId()
    {
        $exitCode = Artisan::call(
            'note:edit',
            [
                'id' => '10000',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unable to find note with this criteria', Artisan::output());
    }

    #[group('integration')]
    public function testNoteEditCommandShouldPassWithHiddenAttributeSetToTrue()
    {
        $ticket = Ticket::factory()->create();
        $note = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester',
            'text'      => 'This is a test note created by the unit tests.',
            'hidden'    => false,
            'viewed'    => false,
        ]);

        $this->assertFalse((bool) $note->hidden);

        $exitCode = Artisan::call(
            'note:edit',
            [
                'id'       => $note->id,
                '--hidden' => true,
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Note updated successfully', Artisan::output());
        $this->assertTrue((bool) Note::find($note->id)->hidden);
    }

    #[group('integration')]
    public function testNoteEditCommandShouldPassWithViewedAttributeIsSetToTrueAfterCommand() {
        $ticket = Ticket::factory()->create();
        $note = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester',
            'text'      => 'This is a test note created by the unit tests.',
            'hidden'    => false,
            'viewed'    => false,
        ]);

        $exitCode = Artisan::call(
            'note:edit',
            [
                'id'       => $note->id,
                '--viewed' => 'true',
            ]
        );

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Note updated successfully', Artisan::output());
        $this->assertTrue((bool) Note::find($note->id)->viewed);
    }
}
