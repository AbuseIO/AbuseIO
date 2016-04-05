<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Role;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ShowCommand.
 */
class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}.
     */
    protected function getAsNoun()
    {
        return 'role';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['role'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getFields()
    {
        return [
            'id',
            'name',
            'description',
        ];
    }

    /**
     * {@inherit docs}.
     */
    protected function getCollectionWithArguments()
    {
        return Role::where('id', $this->argument('role'))->orWhere('name', $this->argument('role'));
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'role',
                InputArgument::REQUIRED,
                'Use the id or the name for a role to show it.'
            ),
        ];
    }

    /**
     * {@inherit docs}.
     */
    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);

        $permissions = '';
        foreach ($model->permissions as $permission) {
            $permissions .= $permission->name.PHP_EOL;
        }
        $result[] = ['Permissions', $permissions];

        return $result;
    }
}
