<?php

namespace AbuseIO\Console\Commands\Role;

use Illuminate\Console\Command;
use AbuseIO\Models\Role;
use Validator;
use Carbon;

class EditCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:edit
                            {--role= : The role name or id of the role you want to change }
                            {--name= : The new name of the role }
                            {--description= : The new description of the role }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Changes information from a role';

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
            $this->warn('the required role argument was not passed, try help');
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

        // Apply changes to the role object
        if (!empty($this->option('name'))) {
            $role->name = $this->option('name');
        }
        if (!empty($this->option('description'))) {
            $role->description = $this->option('description');
        }

        // Validate the changes
        $roleChange = $role->toArray();

        $validation = Validator::make($roleChange, $role->updateRules($roleChange));

        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error('Failed to create the role due to validation warnings');

            return false;
        }

        // Save the object
        $role->save();

        $this->info("Role has been successfully updated");

        return true;
    }
}
