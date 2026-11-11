<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Domain;
use Illuminate\Console\Command;

class DeleteDomain extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:delete {id : The ID of the domain to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a domain from the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $domain = Domain::where('id', $inputArgumentId)->first();

        if (!$domain) {
            $this->error("Unable to find domain.");
            return $this->getNotFoundExitCode();
        }

        if (!$domain->delete()) {
            $this->error("Failed to delete domain.");
            return $this->getDeleteFailedExitCode();
        }
        $this->info("Domain has been deleted");
        return $this->getSuccessExitCode();
    }
}
