<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\AbstractEditCommand;
use AbuseIO\Models\Brand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class EditCommand extends AbstractEditCommand
{
    public function getOptionsList()
    {
        return new InputDefinition([
            new inputArgument('id', null, 'Brand id to edit'),
            new InputOption('name', null, InputOption::VALUE_OPTIONAL, 'brand name'),
            new InputOption('company_name', null, InputOption::VALUE_OPTIONAL,  'company name'),
            new InputOption('introduction_text', null, InputOption::VALUE_OPTIONAL, 'Introduction text'),
        ]);
    }

    public function getAsNoun()
    {
        return 'brand';
    }

    protected function getModelFromRequest()
    {
        $model = Brand::find($this->argument('id'));

        $this->updateFieldWithOption($model, 'name');
        $this->updateFieldWithOption($model, 'company_name');
        $this->updateFieldWithOption($model, 'introduction_text');

        return $model;
    }

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Brand::updateRules($model));
    }
}
