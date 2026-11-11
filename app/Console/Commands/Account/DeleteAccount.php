<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Account;
use Illuminate\Console\Command;

class DeleteAccount extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:delete {id : ID of the account to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes an account';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentId = $this->argument('id');
        $account = Account::where('id', $inputArgumentId)->first();

        if (!$account) {
            $this->error('Account not found.');
            return $this->getNotFoundExitCode();
        }

        if ($account->delete()) {
            $this->info('Account deleted successfully.');
            return $this->getSuccessExitCode();
        } else {
            $this->error('Failed to delete the account.');
            return $this->getDeleteFailedExitCode();
        }
    }
}
