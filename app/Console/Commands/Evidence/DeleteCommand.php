<?php

namespace AbuseIO\Console\Commands\Evidence;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Evidence;
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
        return 'evidence';
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
        $evidence = Evidence::find($this->argument('id'));

        if ($evidence && $evidence->events->count() > 0) {
            $this->error('couldn\'t delete evidence because it is used in events');

            return null;
        }

        return $evidence;
    }

    /**
     * {@inheritdoc}.
     */
    protected function defineInput()
    {
        return array(
            new InputArgument(
                'id',
                InputArgument::REQUIRED,
                'Use the id for evidence to delete it.'),
        );
    }
}
