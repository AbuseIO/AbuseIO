<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Brand;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DeleteCommand.
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'brand';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAllowedArguments()
    {
        return ['id'];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getObjectByArguments()
    {
        return Brand::find($this->argument('id'));
    }

    /**
     * {@inheritdoc}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'id',
                InputArgument::REQUIRED,
                'Use the id for a brand to delete it.'
            ),
        ];
    }
}
