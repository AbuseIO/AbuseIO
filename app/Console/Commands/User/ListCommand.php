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
    protected $headers = ['ID', 'Account', 'User', 'First Name', 'Last Name', 'Role'];

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
     * @return mixed
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

            $role = $user->roles()->first();
            if (!is_object($role)) {
                $role = 'None';
            } else {
                $role = $role->role_description;
            }

            $account = $user->account()->first();
            if (!is_object($account)) {
                $account = 'None';
            } else {
                $account = $account->name;
            }

            $user = $user->toArray();
            $user['role'] = $role;
            $user['account_id'] = $account;

            $userlist[] = $user;
        }

        $this->table($this->headers, $userlist);
    }
}
