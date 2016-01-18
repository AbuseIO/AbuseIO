<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Brand;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

// TODO logo must be resolved, cann't resolve logo from CLI maybe a default or change required in model?

class CreateCommand extends AbstractCreateCommand
{
    public function getArgumentsList()
    {
        return new InputDefinition([
            new InputArgument('name', null, 'brand name'),
            new InputArgument('company_name', null, 'Company name'),
            new InputArgument('introduction_text', null, 'Introduction text'),
        ]);
    }

    public function getAsNoun()
    {
        return 'brand';
    }

    protected function getModelFromRequest()
    {
        $brand = new Brand();

        $brand->name = $this->argument('name');
        $brand->company_name = $this->argument('company_name');
        $brand->introduction_text = $this->argument('introduction_text');
        $brand->logo = "none";

        return $brand;
    }

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Brand::createRules($model));
    }
}
