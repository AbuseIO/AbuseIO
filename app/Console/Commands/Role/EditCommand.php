<?php

namespace AbuseIO\Console\Commands\Role;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Role;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Validator;

/**
 * Class EditCommand.
 */
class EditCommand extends AbstractEditCommand
{
    /**
     * {@inheritdoc}.
     */
    public function getOptionsList()
    {
        return new InputDefinition(
            [
                new inputArgument('id', InputArgument::REQUIRED, 'Role id to edit'),
                new InputOption('name', null, InputOption::VALUE_OPTIONAL, 'Name for role'),
                new InputOption('description', null, InputOption::VALUE_OPTIONAL, 'Description'),
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
        return Role::find($this->argument('id'));
    }

    /**
     * {@inheritdoc}.
     */
    protected function handleOptions($model)
    {
        $this->updateFieldWithOption($model, 'name');
        $this->updateFieldWithOption($model, 'description');

        return true;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        $data = $this->getModelAsArrayForDirtyAttributes($model);
        $updateRules = $this->getUpdateRulesForDirtyAttributes(Role::updateRules($model));

        return Validator::make($data, $updateRules);
    }
}
