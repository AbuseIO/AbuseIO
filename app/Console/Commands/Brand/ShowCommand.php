<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Brand;
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
        return 'brand';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['id', 'name'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getFields()
    {
        return ['id', 'name', 'company_name', 'introduction_text'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getCollectionWithArguments()
    {
        return Brand::where('name', 'like', '%'.$this->argument('brand').'%')
            ->orWhere('id', $this->argument('brand'))->get($this->getFields());
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'brand',
                InputArgument::REQUIRED,
                'Use the id or name for a brand to show it.'
            ),
        ];
    }
}
