<?php

namespace AbuseIO\Console\Commands\Job;

use AbuseIO\Console\Commands\AbstractDeleteCommand;
use AbuseIO\Models\Job;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class DeleteCommand
 * @package AbuseIO\Console\Commands\Account
 */
class DeleteCommand extends AbstractDeleteCommand
{
    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "job";
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
        return Job::find($this->argument("id"));
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
                'Use the id for a job to delete it.')
        );
    }
}
