<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Note;
use Illuminate\Console\Command;

class ShowNote extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'note:show {note : The id of the note to show}
                                      {--json : Output the note in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a note based on the provided id';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('note');

        $note = Note::where('id', $inputArgumentId)->first();

        if (!$note) {
            $this->error('No matching note was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($note->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $note->id],
                    ['Ticket id', $note->ticket_id],
                    ['Submitter', $note->submitter],
                    ['Text', $note->text],
                    ['Hidden', $note->hidden],
                    ['Viewed', $note->viewed],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
