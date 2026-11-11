<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Http\Requests\UpdateNoteRequest;
use AbuseIO\Models\Note;
use Illuminate\Console\Command;
use Validator;

class EditNote extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'note:edit 
                            {id : Note id to edit}
                            {--hidden= : Hidden} 
                            {--viewed= : Viewed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edits an existing note in the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $inputOptionHidden = $this->option('hidden');
        $inputOptionViewed = $this->option('viewed');

        if (gettype($inputOptionHidden) === 'string') {
            $inputOptionHidden = $inputOptionHidden === 'true';
        }

        if (gettype($inputOptionViewed) === 'string') {
            $inputOptionViewed = $inputOptionViewed === 'true';
        }

        $note = Note::where('id', $inputArgumentId)->first();
        if (!$note) {
            $this->error('Unable to find note with this criteria');
            return $this->getNotFoundExitCode();
        }

        $note->hidden = $inputOptionHidden ?? $note->hidden;
        $note->viewed = $inputOptionViewed ?? $note->viewed;

        $request = new UpdateNoteRequest();
        $request = $request->merge(['id' => $inputArgumentId]);
        $validator = Validator::make($note->toArray(), $request->rules());

        if($validator->fails()) {
            $this->error('Validation failed: ' . implode(', ', $validator->errors()->all()));
            return $this->getValidationFailedExitCode();
        }

        if ($note->update()) {
            $this->info('Note updated successfully');
            return $this->getSuccessExitCode();
        }

        $this->error('Failed to update the note');
        return $this->getSaveFailedExitCode();
    }
}
