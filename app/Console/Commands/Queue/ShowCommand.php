<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Job;
use Illuminate\Console\Command;

/**
 * Class ShowCommand.
 */
class ShowCommand extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'queue:show
                            {queue : use the name of the queue to list the jobs}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a queue';

    /**
     * The headers for the table output.
     *
     * @var array
     */
    private $headers = ['Id', 'Queue', 'Method', 'Attempts', 'Created at'];

    /**
     * @return bool
     */
    public function handle()
    {
        $jobs = $this->findWithCondition($this->argument('queue'));

        if ($jobs->count() === 0) {
            $this->error(
                'No matching queue was found.'
            );

            return false;
        }

        $this->table(
            $this->headers,
            $this->transformListToTableBody($jobs)
        );

        return true;
    }

    /**
     * @param $list
     *
     * @return array
     */
    protected function transformListToTableBody($list)
    {
        $result = [];

        foreach ($list as $job) {
            $method = '';
            if ($job->payload) {
                $payload = unserialize(json_decode($job->payload)->data->command);
                $method = class_basename($payload);
            }

            $result[] = [
                $job->id,
                $job->queue,
                $method,
                $job->attempts,
                $job->created_at,
            ];
        }

        return $result;
    }

    /**
     * @param $filter
     *
     * @return mixed
     */
    protected function findWithCondition($filter)
    {
        return Job::where('queue', 'like', "%{$filter}%")->get();
    }
}
