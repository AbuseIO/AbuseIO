<?php

namespace tests\Models;

use AbuseIO\Models\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class NoteTest extends TestCase
{
    use DatabaseTransactions;

    public function testModelFactory()
    {
        $note = Note::factory()->create();
        $noteFromDB = Note::where('submitter', $note->submitter)->first();
        $this->assertEquals($note->submitter, $noteFromDB->submitter);
    }
}
