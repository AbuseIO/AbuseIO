<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\User;
use Illuminate\Console\Command;

class ShowUser extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:show {user : Use the id for a user to show it.}
                                      {--json : Output the user in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a user based on the provided ID';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentUser = $this->argument('user');
        $user = User::select('id', 'first_name', 'last_name', 'email', 'account_id', 'locale', 'disabled')
            ->where('id', $inputArgumentUser)
            ->first();

        if (!$user) {
            $this->error('No matching user was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($user->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $user->id],
                    ['First name', $user->first_name],
                    ['Last name', $user->last_name],
                    ['Email', $user->email],
                    ['Account ID', $user->account_id],
                    ['Locale', $user->locale],
                    ['Disabled', $user->disabled],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
