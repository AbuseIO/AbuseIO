<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Note;
use Illuminate\Console\Command;

class ListNote extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'note:list {--filter= : Filter notes by id or submitter}
                                      {--json : Output the notes in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all notes or listed notes based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputOptionFilter = $this->option('filter');
        $noteSelectQuery = Note::select('id', 'ticket_id', 'submitter', 'text', 'hidden', 'viewed');

        if ($inputOptionFilter) {
            $notes = $noteSelectQuery->where('id', $inputOptionFilter)
                ->orWhere('submitter', 'like', '%' . $inputOptionFilter . '%')
                ->get();
        } else {
            $notes = $noteSelectQuery->get();
        }

        if (empty($notes) || $notes->count() == 0) {
            $this->info('No notes found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($notes->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Id', 'Ticket id', 'Submitter', 'text', 'Hidden', 'Viewed'],
                $notes->map(function ($note) {
                    return [
                        $note->id,
                        $note->ticket_id,
                        $note->submitter,
                        $note->text,
                        $note->hidden,
                        $note->viewed,
                    ];
                })->toArray()
            );
        }

        return $this->getSuccessExitCode();
    }
}
