<?php

namespace AbuseIO\Console\Commands\Permission;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Permission;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "permission";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["id"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return [
            'id',
            'name',
            'description',
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return Permission::Where("id", $this->argument("permission"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'permission',
                InputArgument::REQUIRED,
                'Use the id for a permission to show it.')
        ];
    }
    /**
     * {@inherit docs}
     */
    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);

        $roles = [];
        foreach ($model->roles as $role) {
            $roles[] = $role->name;
        }
        if ($roles) {
            $rolesCaption = 'Roles';
            if (count($roles) === 1) {
                $rolesCaption = 'Role';
            }

            $result[] = [$rolesCaption, implode(PHP_EOL, $roles)];
        }

        return $result;
    }
}



//use Illuminate\Console\Command;
//use AbuseIO\Models\Permission;
//use Carbon;



//class ShowCommand extends Command
//{
//
//    /**
//     * The console command name.
//     * @var string
//     */
//    protected $signature = 'permission:show
//                            {--permission= : Use the permission name or id to show permission details }
//    ';
//
//    /**
//     * The console command description.
//     * @var string
//     */
//    protected $description = 'Shows the details of a permission';
//
//    /**
//     * The headers of the table
//     * @var array
//     */
//    protected $headers = ['ID', 'Name', 'Description', 'Roles'];
//
//    /**
//     * The fields of the table / database row
//     * @var array
//     */
//    protected $fields = ['id', 'name', 'description', 'permissions'];
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
//        if (empty($this->option('permission'))) {
//            $this->warn('no email or id argument was passed, try --help');
//            return false;
//        }
//
//        $permission = false;
//        if (!is_object($permission)) {
//            $permission = Permission::where('name', $this->option('permission'))->first();
//        }
//
//        if (!is_object($permission)) {
//            $permission = Permission::find($this->option('permission'));
//        }
//
//        if (!is_object($permission)) {
//            $this->error('Unable to find permission with this criteria');
//            return false;
//        }
//
//        $roles = '';
//        foreach ($permission->roles as $role) {
//            $roles .= $role->name . PHP_EOL;
//        }
//
//        $table = [ ];
//        $counter = 0;
//        foreach (array_combine($this->headers, $this->fields) as $header => $field) {
//            $counter++;
//            if ($header == 'Roles') {
//                $table[$counter][] = $header;
//                $table[$counter][] = chop($roles);
//            } else {
//                $table[$counter][] = $header;
//                $table[$counter][] = (string)$permission->$field;
//            }
//        }
//
//        $this->table(['Permission Setting', 'Permission Value'], $table);
//
//        return true;
//    }
//}
