<?php

namespace AbuseIO\Console\Commands\Role;

use Illuminate\Console\Command;
use AbuseIO\Models\Role;
use Carbon;

class ShowCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:show
                            {--role= : Use the role name or id to show role details }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows the details of a role';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['ID', 'Name', 'Description', 'Permissions'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['id', 'name', 'description', 'permissions'];

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
        if (empty($this->option('role'))) {
            $this->warn('no email or id argument was passed, try help');
            return false;
        }

        $role = false;
        if (!is_object($role)) {
            $role = Role::where('name', $this->option('role'))->first();
        }

        if (!is_object($role)) {
            $role = Role::find($this->option('role'));
        }

        if (!is_object($role)) {
            $this->error('Unable to find role with this criteria');
            return false;
        }

        $permissions = '';
        foreach ($role->permissions as $permission) {
            $permissions .= $permission->name . PHP_EOL;
        }

        $table = [ ];
        $counter = 0;
        foreach (array_combine($this->headers, $this->fields) as $header => $field) {
            $counter++;
            if ($header == 'Permissions') {
                $table[$counter][] = $header;
                $table[$counter][] = chop($permissions);
            } else {
                $table[$counter][] = $header;
                $table[$counter][] = (string)$role->$field;
            }
        }

        $this->table(['Role Setting', 'Role Value'], $table);

        return true;
    }
}
