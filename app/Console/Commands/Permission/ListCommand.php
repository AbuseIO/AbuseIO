<?php

namespace AbuseIO\Console\Commands\Permission;


use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Permission;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Permission
 */
class ListCommand extends AbstractListCommand
{

    protected $filterArguments = ["id"];


    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', "Name", "Description"];

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $permission  \AbuseIO\Models\Permission|null */
        foreach ($list as $permission) {
            $result[] = [
                $permission->id,
                $permission->name,
                $permission->description,
            ];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Permission::where('id',  $filter)
            ->orWhere('name', 'like', '%'.$filter.'%')
            ->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return Permission::all();
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "permission";
    }
}



//use Illuminate\Console\Command;
//use AbuseIO\Models\Permission;
//use Carbon;
//
//class ListCommand extends Command
//{
//
//    /**
//     * The console command name.
//     * @var string
//     */
//    protected $signature = 'permission:list
//                            {--filter : Applies a filter on the permission name }
//    ';
//
//    /**
//     * The console command description.
//     * @var string
//     */
//    protected $description = 'Shows a list of all available permissions';
//
//    /**
//     * The headers of the table
//     * @var array
//     */
//    protected $headers = ['ID', 'Name', 'Description'];
//
//    /**
//     * The fields of the table / database row
//     * @var array
//     */
//    protected $fields = ['id', 'name', 'description'];
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
//        if (!empty($this->option('filter'))) {
//            $permissions = Permission::where('name', 'like', "%{$this->option('filter')}%")->get($this->fields);
//        } else {
//            $permissions = Permission::all($this->fields);
//        }
//
//        $this->table($this->headers, $permissions);
//
//        return true;
//    }
//}
