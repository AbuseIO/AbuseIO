<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Group;
use tests\TestCase;

/**
 * Class ListCommandTest.
 *
 * @note In this test we use the ticket factory to create tickets for the notes.
 *     - The tickets model is really large to set up so we use factories to keep the test code clean.
 */
class ListCommandTest extends TestCase
{
    use RefreshDatabase;

    #[group('functional')]
    public function testNoteListCommandShouldPassShowingTableHeaders(): void
    {
        $headers = ['Id', 'Ticket id', 'Submitter', 'text', 'Hidden', 'Viewed'];
        $ticket = Ticket::factory()->create();
        Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester',
            'text' => 'This is a test note to be deleted by the unit tests.',
            'hidden' => false,
            'viewed' => false,
        ]);

        $exitCode = Artisan::call('note:list', []);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        foreach ($headers as $header) {
            $this->assertStringContainsString($header, $output);
        }
    }

    #[group('functional')]
    public function testNoteListCommandShouldPassWithTwoNotes(): void
    {
        $ticket = Ticket::factory()->create();
        $noteOne = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester',
            'text' => 'This is a test note to be deleted by the unit tests.',
            'hidden' => false,
            'viewed' => false,
        ]);
        $noteTwo = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester',
            'text' => 'This is a test note to be deleted by the unit tests.',
            'hidden' => false,
            'viewed' => false,
        ]);

        $exitCode = Artisan::call('note:list', []);
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($noteOne->submitter, $output);
        $this->assertStringContainsString($noteTwo->submitter, $output);
    }

    #[group('functional')]
    public function testNoteListCommandShouldPassWithFiltering(): void
    {
        $ticket = Ticket::factory()->create();
        $noteOne = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester One',
            'text' => 'This is a test note to be deleted by the unit tests.',
            'hidden' => false,
            'viewed' => false,
        ]);
        $noteTwo = Note::create([
            'ticket_id' => $ticket->id,
            'submitter' => 'Tester Two',
            'text' => 'This is a test note to be deleted by the unit tests.',
            'hidden' => false,
            'viewed' => false,
        ]);

        $exitCode = Artisan::call(
            'note:list',
            [
                '--filter' => $noteOne->submitter,
            ]
        );
        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString($noteOne->submitter, $output);
        $this->assertStringNotContainsString($noteTwo->submitter, $output);
    }

    #[group('functional')]
    public function testNoteListCommandShouldFailWithInvalidFilter(): void
    {
        $exitCode = Artisan::call(
            'note:list',
            [
                '--filter' => 'xxx',
            ]
        );

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('No notes found.', Artisan::output());
    }
}
