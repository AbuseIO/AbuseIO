<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Netblock;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class EditCommand extends AbstractEditCommand
{

    public function getOptionsList()
    {
        return new InputDefinition([
            new inputArgument('id', null, 'Netblock id to edit'),
            new InputOption('contact_id', null, InputOption::VALUE_OPTIONAL, 'Id for contact'),
            new InputOption('first_ip', null, InputOption::VALUE_OPTIONAL,  'First ip'),
            new InputOption('last_ip', null, InputOption::VALUE_OPTIONAL, 'Last ip'),
            new InputOption('description',  null, InputOption::VALUE_OPTIONAL,  'Description'),
            new InputOption('enabled',  null, InputOption::VALUE_OPTIONAL,  'Enabled')
        ]);
    }

    public function getAsNoun()
    {
        return 'netblock';
    }

    protected function getModelFromRequest()
    {
        return Netblock::find($this->argument('id'));
    }

    protected function handleOptions($model)
    {
        $this->updateFieldWithOption($model, 'contact_id');
        $this->updateFieldWithOption($model, 'first_ip');
        $this->updateFieldWithOption($model, 'last_ip');
        $this->updateFieldWithOption($model, 'description');
        $this->updateFieldWithOption($model, 'enabled');

        return $model;
    }

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Netblock::updateRules($model));
    }
}
