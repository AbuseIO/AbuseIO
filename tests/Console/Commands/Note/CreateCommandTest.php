<?php

namespace tests\Console\Commands\Note;

use AbuseIO\Models\Note;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;
use tests\TestCase;

/**
 * Class CreateCommandTest.
 */
class CreateCommandTest extends TestCase
{
    public function testCreate()
    {
        /** @var Note $dummy */
        $dummy = factory(Note::class)->make();

        $exitCode = Artisan::call(
            'note:create',
            [
                'ticket_id' => $dummy->ticket->id,
                'submitter' => $dummy->submitter,
                'text'      => $dummy->text,
                'hidden'    => $dummy->hidden,
                'viewed'    => $dummy->viewed,
            ]
        );

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('created', Artisan::output());

        Note::where([
            'ticket_id' => $dummy->ticket->id,
            'submitter' => $dummy->submitter,
            'text'      => $dummy->text,
            'hidden'    => $dummy->hidden,
            'viewed'    => $dummy->viewed,
        ])->forceDelete();
    }

    public function testWithoutArguments()
    {
        ob_start();
        $exitCode = Artisan::call('note:create');
        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Creates a new note', ob_get_clean());
    }
}
