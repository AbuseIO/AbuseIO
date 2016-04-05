<?php

namespace AbuseIO\Console\Commands\Permission;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Permission;
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
        return 'permission';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['id'];
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
        return Permission::Where('id', $this->argument('permission'));
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'permission',
                InputArgument::REQUIRED,
                'Use the id for a permission to show it.'
            ),
        ];
    }

    /**
     * {@inherit docs}.
     */
    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);

        $roles = [];
        foreach ($model->roles as $role) {
            $roles[] = $role->name;
        }
        if ($roles) {
            $rolesCaption = 'Roles';
            if (count($roles) === 1) {
                $rolesCaption = 'Role';
            }

            $result[] = [$rolesCaption, implode(PHP_EOL, $roles)];
        }

        return $result;
    }
}
