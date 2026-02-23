<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\StoreNoteRequest;
use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use Illuminate\Console\Command;
use Validator;

class CreateNote extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'note:create
                            {ticket_id : Ticket id for the note}
                            {submitter : Submitter of the note}
                            {text : Text of the note}
                            {viewed? : Whether the note is viewed}
                            {hidden? : Whether the note is hidden}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new note for a ticket in the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentTicketId = $this->argument('ticket_id');
        $inputArgumentSubmitter = $this->argument('submitter');
        $inputArgumentText = $this->argument('text');
        $inputArgumentViewed = $this->argument('viewed') ?? 'false';
        $inputArgumentHidden = $this->argument('hidden') ?? 'false';

        if (gettype($inputArgumentViewed) === 'string') {
            $inputArgumentViewed = $inputArgumentViewed === 'true';
        }

        if (gettype($inputArgumentHidden) === 'string') {
            $inputArgumentHidden = $inputArgumentHidden === 'true';
        }

        $ticket = Ticket::where('id', $inputArgumentTicketId)->first();
        if (!$ticket) {
            $this->error("Could not find the ticket.");
            return $this->getNotFoundExitCode();
        }

        $note = Note::make([
            'ticket_id' => $inputArgumentTicketId,
            'submitter' => $inputArgumentSubmitter,
            'text' => $inputArgumentText,
            'viewed' => $inputArgumentViewed,
            'hidden' => $inputArgumentHidden,
        ]);

        $validator = Validator::make($note->toArray(), new StoreNoteRequest()->rules());

        if ($validator->fails()) {
            $this->error("Validation failed: " . implode(", ", $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if ($note->save()) {
            $this->info("Note created successfully.");
            return $this->getSuccessExitCode();
        }

        $this->error("Failed to save the note.");
        return $this->getSaveFailedExitCode();
    }
}
