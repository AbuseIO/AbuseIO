<?php

namespace AbuseIO\Console\Commands\Role;

use Illuminate\Console\Command;
use AbuseIO\Models\Role;
use Carbon;

class CreateCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'role:create
                            {--name= : The name of the role }
                            {--description= : The description of the role }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Creates a new role';

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
        if (empty($this->option('name')) ||
            empty($this->option('description'))
        ) {
            $this->error('Missing options for name and/or descrption');
            return false;
        }

        $roleadd = [
            'role_name'         => empty($this->option('name')) ? false : $this->option('name'),
            'role_description'  => empty($this->option('description')) ? false : $this->option('description'),
        ];

        $role = new Role();
        $validation = $role->validateCreate($roleadd);

        if ($validation->fails()) {
            foreach ($validation->messages()->all() as $message) {
                $this->warn($message);
            }

            $this->error('Failed to create the role due to validation warnings');

            return false;
        }

        $role->fill($roleadd);

        if (!$role->save()) {
            $this->error('Failed to save the role into the database');

            return false;
        }

        $this->info("The role {$this->option('name')} has been created");

        return true;
    }
}
