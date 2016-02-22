<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Role;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class CreateCommand extends AbstractCreateCommand
{
    // TODO validation of file not working
    public function getArgumentsList()
    {
        return new InputDefinition([
            new InputArgument('name', null, 'Name'),
            new InputArgument('description', null, 'Description')
        ]);
    }

    public function getAsNoun()
    {
        return 'role';
    }

    protected function getModelFromRequest()
    {
        $role = new Role();

        $role->name = $this->argument('name');
        $role->description = $this->argument('description');

        return $role;
    }

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Role::createRules($model));
    }
}


//use Illuminate\Console\Command;
//use AbuseIO\Models\Role;
//use Carbon;
//use Validator;
//
//class CreateCommand extends Command
//{
//
//    /**
//     * The console command name.
//     * @var string
//     */
//    protected $signature = 'role:create
//                            {--name= : The name of the role }
//                            {--description= : The description of the role }
//    ';
//
//    /**
//     * The console command description.
//     * @var string
//     */
//    protected $description = 'Creates a new role';
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
//        if (empty($this->option('name')) ||
//            empty($this->option('description'))
//        ) {
//            $this->error('Missing options for name and/or descrption');
//            return false;
//        }
//
//        $role = new Role();
//
//        $role->name         = empty($this->option('name')) ? false : $this->option('name');
//        $role->description  = empty($this->option('description')) ? false : $this->option('description');
//
//        $validation = Validator::make($role->toArray(), Role::createRules($role));
//
//        if ($validation->fails()) {
//            foreach ($validation->messages()->all() as $message) {
//                $this->warn($message);
//            }
//
//            $this->error('Failed to create the role due to validation warnings');
//
//            return false;
//        }
//
//        if (!$role->save()) {
//            $this->error('Failed to save the role into the database');
//
//            return false;
//        }
//
//        $this->info("The role {$this->option('name')} has been created");
//
//        return true;
//    }
//}
