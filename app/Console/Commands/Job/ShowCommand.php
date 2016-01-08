<?php

namespace AbuseIO\Console\Commands\Job;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use AbuseIO\Models\Job;
use Symfony\Component\Console\Input\InputArgument;

class ShowCommand extends AbstractShowCommand2
{
    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "job";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["id"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return [
            'id',
            'queue',
            'payload',
            'attempts',
            'reserved',
            'reserved_at',
            'available_at',
            'created_at',
        ];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return Job::Where("id", $this->argument("job"));
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'job',
                InputArgument::REQUIRED,
                'Use the id for a job to show it.')
        ];
    }
}
