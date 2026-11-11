<?php

namespace tests\Models;

use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class NoteTest.
 *
 * @note In this test we use the ticket factory to create tickets for the notes.
 *       - The tickets model is really large to set up so we use factories to keep the test code clean.
 */

class NoteTest extends TestCase
{
    use RefreshDatabase;

    #[group('integration')]
    public function testNoteModelCreation()
    {
        $ticket = Ticket::factory()->create();
        $note = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester',
            'text' => 'This is a test note to be deleted by the unit tests.',
            'hidden' => false,
            'viewed' => false,
        ]);
        $noteFromDB = Note::where('submitter', $note->submitter)->first();
        $this->assertEquals($note->submitter, $noteFromDB->submitter);
    }

    #[group('functional')]
    public function testNoteViewedAttributeIsFalse() {
        $note = Note::make([
            'ticket_id' => 1,
            'submitter' => 'Tester',
            'text'      => 'This is a test note created by the unit tests.',
            'hidden'    => false,
            'viewed'    => false,
        ]);

        $this->assertFalse($note->viewed);
    }

    #[group('functional')]
    public function testNoteViewedAttributeIsTrue() {
        $note = Note::make([
            'ticket_id' => 1,
            'submitter' => 'Tester',
            'text'      => 'This is a test note created by the unit tests.',
            'hidden'    => false,
            'viewed'    => true,
        ]);

        $this->assertTrue($note->viewed);
    }

    #[group('functional')]
    public function testNoteHiddenAttributeIsFalse() {
        $note = Note::make([
            'ticket_id' => 1,
            'submitter' => 'Tester',
            'text'      => 'This is a test note created by the unit tests.',
            'hidden'    => false,
            'viewed'    => false,
        ]);

        $this->assertFalse($note->hidden);
    }

    #[group('functional')]
    public function testNoteHiddenAttributeIsTrue() {
        $note = Note::make([
            'ticket_id' => 1,
            'submitter' => 'Tester',
            'text'      => 'This is a test note created by the unit tests.',
            'hidden'    => true,
            'viewed'    => false,
        ]);

        $this->assertTrue($note->hidden);
    }
}
