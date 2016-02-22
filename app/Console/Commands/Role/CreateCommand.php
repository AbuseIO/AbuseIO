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
