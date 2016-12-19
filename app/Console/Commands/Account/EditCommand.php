<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Validator;

/**
 * Class EditCommand.
 */
class EditCommand extends AbstractEditCommand
{
    use ShowHelpWhenRunTimeExceptionOccurs;

    /**
     * @return InputDefinition
     */
    public function getOptionsList()
    {
        return new InputDefinition(
            [
                new inputArgument('id', inputArgument::REQUIRED, 'Account id to edit'),
                new InputOption('name', null, InputOption::VALUE_OPTIONAL, 'account name'),
                new InputOption('brand_id', null, InputOption::VALUE_OPTIONAL, 'brand id'),
                new InputOption(
                    'disabled',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    'true|false, Set the account to be enabled.'
                ),
                new InputOption(
                    'systemaccount',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    'true|false, Set default system account.'
                ),
                new InputOption(
                    'refres_api_token',
                    null,
                    InputOption::VALUE_NONE,
                    'Refresh the api token for this account.'
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function getAsNoun()
    {
        return 'account';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getModelFromRequest()
    {
        return Account::find($this->argument('id'));
    }

    /**
     * {@inheritdoc}.
     */
    protected function handleOptions($model)
    {
        $this->updateFieldWithOption($model, 'name');

        if ($this->option('brand_id')) {
            if (null === Brand::find($this->option('brand_id'))) {
                $this->error('Unable to find brand with this criteria');

                return false;
            }
        }

        $this->setSystemAccount($model);
        $this->updateFieldWithOption($model, 'brand_id');
        $this->updateBooleanFieldWithOption($model, 'disabled');

        return true;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Account::updateRules($model));
    }

    /**
     * {@inheritdoc}.
     */
    private function setSystemAccount($model)
    {
        if ($this->option('systemaccount') == true) {
            /* @var Account $model */
            $model->systemaccount = true;
        }
    }
}
