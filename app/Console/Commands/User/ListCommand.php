<?php

namespace AbuseIO\Console\Commands\User;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\User;

class ListCommand extends AbstractListCommand
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
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        return $list;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        $users = User::where('email', 'like', "%{$filter}%")->get($this->fields);
        return $this->hydrateWithRoles($users);
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        $users = User::all($this->fields);
        return $this->hydrateWithRoles($users);
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "user";
    }

    /**
     * @param $users
     */
    private function hydrateWithRoles($users)
    {
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
        return $userlist;
    }
}
