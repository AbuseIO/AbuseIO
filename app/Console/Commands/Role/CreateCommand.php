<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Role;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

/**
 * Class CreateCommand.
 */
class CreateCommand extends AbstractCreateCommand
{
    // TODO validation of file not working

    /**
     * {@inheritdoc}.
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('name', InputArgument::REQUIRED, 'Name'),
                new InputArgument('description', InputArgument::REQUIRED, 'Description'),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        $role = new Role();

        $role->name = $this->argument('name');
        $role->description = $this->argument('description');

        return $role;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Role::createRules());
    }
}
