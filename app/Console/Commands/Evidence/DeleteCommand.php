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

        return $evidence;
    }

    /**
     * {@inheritdoc }
     */
    protected function stopDeleteAndThrowAnErrorBecauseRelations($object)
    {
        if ($object->events->count() > 0) {
            $this->error('Couldn\'t delete evidence because it is used in events');

            return true;
        }

        return false;
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
                'Use the id for evidence to delete it.'
            ),
        );
    }
}
