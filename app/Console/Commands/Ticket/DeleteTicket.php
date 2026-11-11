<?php

namespace AbuseIO\Console\Commands\Ticket;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Ticket;
use Illuminate\Console\Command;

class DeleteTicket extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:delete {id : Use the id for a ticket to delete it.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a ticket from the system based on the provided ID';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $ticket = Ticket::where('id', $inputArgumentId)->first();

        if (!$ticket) {
            $this->error("Unable to find ticket.");
            return $this->getNotFoundExitCode();
        }

        if (!$ticket->delete()) {
            $this->error("Failed to delete the ticket.");
            return $this->getDeleteFailedExitCode();
        }

        $this->info("The ticket has been deleted from the system.");
        return $this->getSuccessExitCode();
    }
}
