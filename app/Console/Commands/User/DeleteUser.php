<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\User;
use Illuminate\Console\Command;

class DeleteUser extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete {user : Use the name or email for a user to delete it.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a user from the system based on the provided name or email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentUser = $this->argument('user');
        $user = User::where('email', $inputArgumentUser)
            ->orWhere('id', $inputArgumentUser)
            ->first();

        if (!$user) {
            $this->error("Unable to find user");
            return $this->getNotFoundExitCode();
        }

        if (!$user->delete()) {
            $this->error("Failed to delete the user");
            return $this->getDeleteFailedExitCode();
        }

        $this->info("The user has been deleted from the system");
        return $this->getSuccessExitCode();
    }
}
