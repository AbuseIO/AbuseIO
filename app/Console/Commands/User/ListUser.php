<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\User;
use Illuminate\Console\Command;

class ListUser extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list {--filter= : Filter users by email}
                                      {--json : Output in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all users or listed users based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputOptionFilter = $this->option('filter');
        $userSelectQuery = User::select('id', 'account_id', 'email', 'first_name', 'last_name');

        if ($inputOptionFilter) {
            $users = $userSelectQuery->where('email', 'like', "%{$inputOptionFilter}%")->get();
        } else {
            $users = $userSelectQuery->get();
        }

        if (empty($users) || $users->count() === 0) {
            $this->info('No users found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($users->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['ID', 'Account', 'User', 'First Name', 'Last Name', 'Roles'],
                $users->map(function ($user) {
                    return [
                        $user->id,
                        $user->account_id,
                        $user->email,
                        $user->first_name,
                        $user->last_name,
                        implode(', ', $user->roles()->get()->pluck('description')->toArray()),
                    ];
                })->toArray()
            );
        }

        return $this->getSuccessExitCode();
    }
}
