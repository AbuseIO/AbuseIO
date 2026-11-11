<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Netblock;
use Illuminate\Console\Command;

class DeleteNetblock extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netblock:delete {id : The ID of the netblock to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a netblock from the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $netblock = Netblock::where('id', $inputArgumentId)->first();

        if (!$netblock) {
            $this->error("Unable to find netblock.");
            return $this->getNotFoundExitCode();
        }

        if (!$netblock->delete()) {
            $this->error("Failed to delete netblock.");
            return $this->getDeleteFailedExitCode();
        }

        $this->info("Netblock has been deleted.");
        return $this->getSuccessExitCode();
    }
}
