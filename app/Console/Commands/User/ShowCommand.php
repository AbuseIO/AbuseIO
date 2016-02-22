<?php

namespace AbuseIO\Console\Commands\User;


use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\User;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "user";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["user"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return [
            'id',
            'first_name',
            'last_name',
            'email',
            'account_id',
            'locale',
            'disabled',
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return User::Where("id", $this->argument("user"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'user',
                InputArgument::REQUIRED,
                'Use the id for a user to show it.')
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);

        $result = $this->hideProperty($result, "Password");
        $result = $this->hideProperty($result, "Account id");
        $result = $this->hideProperty($result, "Remember token");

        $result[] = ["Account", $model->account->name];

        $roleList = [];
        foreach ($model->roles as $role) {
            $roleList[] = $role->description;
        }

        if ($roleList) {
            $roleCaption = "Roles";
            if (count($roleList) === 1) {
                $roleCaption = "Role";
            }
            $result[] = [$roleCaption, implode(PHP_EOL, $roleList)];
        }

        return $result;
    }


}



//use Illuminate\Console\Command;
//use AbuseIO\Models\User;
//use Carbon;
//
//class ShowCommand extends Command
//{
//
//    /**
//     * The console command name.
//     * @var string
//     */
//    protected $signature = 'user:show
//                            {--user= : Use the user email or id to show user details }
//    ';
//
//    /**
//     * The console command description.
//     * @var string
//     */
//    protected $description = 'Shows the details of an user';
//
//    /**
//     * The headers of the table
//     * @var array
//     */
//    protected $headers = ['User ID', 'User', 'Account', 'Roles', 'First Name', 'Last Name', 'Language', 'Disabled',
//        'Created', 'Last modified'];
//
//    /**
//     * The fields of the table / database row
//     * @var array
//     */
//    protected $fields = ['id', 'email', 'account', 'roles', 'first_name', 'last_name', 'locale', 'disabled',
//        'created_at', 'updated_at'];
//
//    /**
//     * Create a new command instance.
//     * @return void
//     */
//    public function __construct()
//    {
//        parent::__construct();
//    }
//
//    /**
//     * Execute the console command.
//     *
//     * @return boolean
//     */
//    public function handle()
//    {
//        if (empty($this->option('user'))) {
//            $this->warn('no email or id argument was passed, try --help');
//            return false;
//        }
//
//        $user = false;
//        if (!is_object($user)) {
//            $user = User::where('email', $this->option('user'))->first();
//        }
//
//        if (!is_object($user)) {
//            $user = User::find($this->option('user'));
//        }
//
//        if (!is_object($user)) {
//            $this->error('Unable to find user with this criteria');
//            return false;
//        }
//
//        $roleList = [];
//        $roles = $user->roles()->get();
//        foreach ($roles as $role) {
//            if (is_object($role)) {
//                $roleList[] = $role->description;
//            }
//        }
//
//        $account = $user->account()->first();
//        if (!is_object($account)) {
//            $account = 'None';
//        } else {
//            $account = $account->name;
//        }
//
//        $table = [ ];
//        $counter = 0;
//        foreach (array_combine($this->headers, $this->fields) as $header => $field) {
//            $counter++;
//            $table[$counter][] = $header;
//
//            if ($header == 'Disabled') {
//                $table[$counter][] = (boolean)$user->$field ? 'YES' : 'NO';
//            } elseif ($header == 'Account') {
//                $table[$counter][] = $account;
//            } elseif ($header == 'Roles') {
//                $table[$counter][] = implode(', ', $roleList);
//            } else {
//                $table[$counter][] = (string)$user->$field;
//            }
//        }
//
//        $userlist[] = $user;
//
//        $this->table(['User Setting', 'User Value'], $table);
//
//        return true;
//    }
//}
