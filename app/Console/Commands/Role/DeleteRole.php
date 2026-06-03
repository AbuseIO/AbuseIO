<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Role;
use Illuminate\Console\Command;

class DeleteRole extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:delete {role : Use the name or the id for a role to delete it.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a role from the system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentRole = $this->argument('role');

        $role = Role::where('name', $inputArgumentRole)
            ->orWhere('id', $inputArgumentRole)
            ->first();

        if (!$role) {
            $this->error("Unable to find role.");
            return $this->getNotFoundExitCode();
        }

        if (!$role->delete()) {
            $this->error("Failed to delete role");
            return $this->getDeleteFailedExitCode();
        }

        $this->info("The role has been deleted.");
        return $this->getSuccessExitCode();
    }
}
