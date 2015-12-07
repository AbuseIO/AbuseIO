<?php

namespace AbuseIO\Console\Commands\Role;

use Illuminate\Console\Command;
use AbuseIO\Models\Role;
use Carbon;

class ListCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:list
                            {--filter : Applies a filter on the role name }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available roles';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['ID', 'Name', 'Description', 'Permissions'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['id', 'name', 'description'];

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
            $roles = Role::where('name', 'like', "%{$this->option('filter')}%")->get($this->fields);
        } else {
            $roles = Role::all($this->fields);
        }

        $rolelist = [];
        foreach ($roles as $role) {

            $permissionCount = $role->permissions()->count();

            $role = $role->toArray();
            $role['permissions'] = $permissionCount;

            $rolelist[] = $role;
        }


        $this->table($this->headers, $rolelist);
    }
}
