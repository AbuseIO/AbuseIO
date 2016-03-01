<?php

namespace AbuseIO\Console\Commands\Brand;

use Symfony\Component\Console\Input\InputArgument;
use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Brand;

/**
 * Class DeleteCommand
 * @package AbuseIO\Console\Commands\Brand
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "brand";
    }

    /**
     * {@inheritdoc }
     */
    protected function getAllowedArguments()
    {
        return ["id"];
    }

    /**
     * {@inheritdoc }
     */
    protected function getObjectByArguments()
    {
        return Brand::find($this->argument("id"));
    }

    /**
     * {@inheritdoc }
     */
    protected function defineInput()
    {
        return array(
            new InputArgument(
                'id',
                InputArgument::REQUIRED,
                'Use the id for a brand to delete it.'
            )
        );
    }
}
