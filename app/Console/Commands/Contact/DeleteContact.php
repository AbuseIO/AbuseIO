<?php

namespace AbuseIO\Console\Commands\Contact;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Contact;
use Illuminate\Console\Command;

class DeleteContact extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:delete {id : Use the id for a contact to delete it.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a contact from the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $contact = Contact::where('id', $inputArgumentId)->first();

        if (!$contact) {
            $this->error('Unable to find contact');
            return $this->getNotFoundExitCode();
        }

        if (!$contact->delete()) {
            $this->error('Unable to delete contact');
            return $this->getDeleteFailedExitCode();
        }

        $this->info('The contact has been deleted from the system');
        return $this->getSuccessExitCode();
    }
}
