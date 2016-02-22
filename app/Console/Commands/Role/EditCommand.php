<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Role;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class EditCommand extends AbstractEditCommand
{

    public function getOptionsList()
    {
        return new InputDefinition([
            new inputArgument('id', InputArgument::REQUIRED, 'Role id to edit'),
            new InputOption('name', null, InputOption::VALUE_OPTIONAL, 'Name for role'),
            new InputOption('description', null, InputOption::VALUE_OPTIONAL,  'Description')
        ]);
    }

    public function getAsNoun()
    {
        return 'role';
    }

    protected function getModelFromRequest()
    {
        return Role::find($this->argument('id'));
    }

    protected function handleOptions($model)
    {
        $this->updateFieldWithOption($model, 'name');
        $this->updateFieldWithOption($model, 'description');

        return true;
    }

    protected function getValidator($model)
    {
        $data = $this->getModelAsArrayForDirtyAttributes($model);
        $updateRules = $this->getUpdateRulesForDirtyAttributes(Role::updateRules($model));

        return Validator::make($data, $updateRules);
    }
}
