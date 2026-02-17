<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Note;
use Illuminate\Console\Command;

class DeleteNote extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'note:delete {id : The ID of the note to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a note from the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $note = Note::where('id', $inputArgumentId)->first();

        if (!$note) {
            $this->error("Unable to find note.");
            return $this->getNotFoundExitCode();
        }

        if ($note->delete()) {
            $this->info("Successfully deleted the note.");
            return $this->getSuccessExitCode();
        }

        $this->error("Failed to delete the note.");
        return $this->getDeleteFailedExitCode();
    }
}
