<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

/**
 * Class CreateCommand.
 */
class CreateCommand extends AbstractCreateCommand
{
    /**
     * @return InputDefinition
     */
    public function getArgumentsList()
    {
        return new InputDefinition(
            [
                new InputArgument('name', InputArgument::REQUIRED, 'brand name'),
                new InputArgument('company_name', InputArgument::REQUIRED, 'Company name'),
                new InputArgument('introduction_text', InputArgument::OPTIONAL, 'Introduction text'),
            ]
        );
    }

    /**
     * @return string
     */
    public function getAsNoun()
    {
        return 'brand';
    }

    /**
     * @return Brand
     */
    protected function getModelFromRequest()
    {
        $brand = new Brand();

        $brand->name = $this->argument('name');
        $brand->company_name = $this->argument('company_name');
        $brand->introduction_text = $this->argument('introduction_text');
        $brand->logo = Brand::getDefaultLogo();
        $brand->creator_id = Account::getSystemAccount()->id;

        return $brand;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Brand::createRules());
    }
}
