<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Role;
use Symfony\Component\Console\Input\InputArgument;


class DeleteCommand extends AbstractDeleteCommand
{

    public function defineInput()
    {
        return array(
                new InputArgument(
                    'role',
                    InputArgument::REQUIRED,
                    'Use the name or the id for a role to delete it.'
                )
        );
    }

    protected function getAllowedArguments()
    {
        return ["name", "id"];
    }

    protected function getObjectByArguments()
    {
        $role = false;
        if (!is_object($role)) {
            $role = Role::where('name', $this->option('role'))->first();
        }

        if (!is_object($role)) {
            $role = Role::find($this->option('role'));
        }
        return $role;
    }

    protected function getAsNoun()
    {
        return "role";
    }
}
