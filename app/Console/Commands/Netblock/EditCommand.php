<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Netblock;
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
                new inputArgument('id', InputArgument::REQUIRED, 'Netblock id to edit'),
                new InputOption('contact_id', null, InputOption::VALUE_OPTIONAL, 'Id for contact'),
                new InputOption('first_ip', null, InputOption::VALUE_OPTIONAL, 'First ip'),
                new InputOption('last_ip', null, InputOption::VALUE_OPTIONAL, 'Last ip'),
                new InputOption('description', null, InputOption::VALUE_OPTIONAL, 'Description'),
                new InputOption('enabled', null, InputOption::VALUE_OPTIONAL, 'Enabled'),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'netblock';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        return Netblock::find($this->argument('id'));
    }

    /**
     * {@inheritdoc}.
     */
    protected function handleOptions($model)
    {
        if ($this->option('contact_id')) {
            if (null === Contact::find($this->option('contact_id'))) {
                $this->error('Unable to find contact with this criteria');

                return false;
            }
        }

        $this->updateFieldWithOption($model, 'contact_id');
        $this->updateFieldWithOption($model, 'first_ip');
        $this->updateFieldWithOption($model, 'last_ip');
        $this->updateFieldWithOption($model, 'description');
        $this->updateBooleanFieldWithOption($model, 'enabled');

        return true;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        $data = $this->getModelAsArrayForDirtyAttributes($model);
        $updateRules = $this->getUpdateRulesForDirtyAttributes(Netblock::updateRules($model));

        return Validator::make($data, $updateRules);
    }
}
