<?php

namespace AbuseIO\Console\Commands\Role;

use Illuminate\Console\Command;
use AbuseIO\Models\Role;
use Carbon;

class DeleteCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:delete
                            {--role= : Use the role name or id to delete it }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Deletes a role (without confirmation!)';

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
        if (empty($this->option('role'))) {
            $this->warn('no name or id argument was passed, try --help');
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

        if (!$role->delete()) {
            $this->error('Unable to delete role from the system');
            return false;
        }

        $this->info('The role has been deleted from the system');

        return true;
    }
}
