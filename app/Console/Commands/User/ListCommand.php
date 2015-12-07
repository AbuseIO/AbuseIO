<?php

namespace AbuseIO\Console\Commands\User;

use Illuminate\Console\Command;
use AbuseIO\Models\User;
use Carbon;

class ListCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'user:list
                            {--filter= : Applies a filter on the email (username login) }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available users';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['ID', 'Account', 'User', 'First Name', 'Last Name', 'Roles'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['id', 'account_id', 'email', 'first_name', 'last_name'];

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {

        if (!empty($this->option('filter'))) {
            $users = User::where('email', 'like', "%{$this->option('filter')}%")->get($this->fields);
        } else {
            $users = User::all($this->fields);
        }

        $userlist = [];
        foreach ($users as $user) {

            $roleList = [];
            $roles = $user->roles()->get();
            foreach ($roles as $role) {
                if (is_object($role)) {
                    $roleList[] = $role->description;
                }
            }

            $account = $user->account()->first();
            if (!is_object($account)) {
                $account = 'None';
            } else {
                $account = $account->name;
            }

            $user = $user->toArray();
            $user['roles'] = implode(', ', $roleList);
            $user['account_id'] = $account;

            $userlist[] = $user;
        }

        $this->table($this->headers, $userlist);

        return true;
    }
}
