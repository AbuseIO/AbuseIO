<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\AbstractShowCommand2;
use Symfony\Component\Console\Input\InputArgument;
use AbuseIO\Models\Job;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Queue
 */
class ShowCommand extends AbstractShowCommand2
{
    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        /*
         * TODO:
         * remove payload and just show the class
         *
         * $payload = unserialize(json_decode($job->payload)->data->command);
         * $method = class_basename($payload);
         *
         * TODO:
         * aanroep queue:show $queue
         *
         * output: alle jobs van deze queue.
         */
        $result = [];

        foreach ($list as $job) {
            $payload = unserialize(json_decode($job->payload)->data->command);
            $method = class_basename($payload);

            $result[] = [
                $job->id,
                $method,
                $job->created_at
            ];
        }
        return $result;
    }

    /**
     * {@inherit docs}
     */
    protected function getAsNoun()
    {
        return "queue";
    }

    /**
     * {@inherit docs}
     */
    protected function getAllowedArguments()
    {
        return ["queue"];
    }

    /**
     * {@inherit docs}
     */
    protected function getFields()
    {
        return ["id", "method", "created"];
    }

    /**
     * {@inherit docs}
     */
    protected function getCollectionWithArguments()
    {
        return Job::where("queue", "like", "%".$this->argument("queue")."%");
    }

    /**
     * {@inherit docs}
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'queue',
                InputArgument::REQUIRED,
                'Use the name for a queue to show it.'
            )
        ];
    }
}
