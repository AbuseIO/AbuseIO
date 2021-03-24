<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Role;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DeleteCommand.
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc}.
     */
    public function defineInput()
    {
        return [
            new InputArgument(
                'role',
                InputArgument::REQUIRED,
                'Use the name or the id for a role to delete it.'
            ),
        ];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAllowedArguments()
    {
        return ['name', 'id'];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getObjectByArguments()
    {
        $role = false;
        if (!is_object($role)) {
            $role = Role::where('name', $this->argument('role'))->first();
        }

        if (!is_object($role)) {
            $role = Role::find($this->argument('role'));
        }

        return $role;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'role';
    }
}
