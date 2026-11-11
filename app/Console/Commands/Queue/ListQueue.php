<?php

namespace AbuseIO\Console\Commands\Queue;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Job;
use Illuminate\Console\Command;

class ListQueue extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:list {--filter= : The name of the queue to filter on}
                                       {--json : Output the queues in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all queues or listed queues based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputFilter = $this->option('filter');
        $queues = config('queue.queues');

        $results = [];
        if ($inputFilter) {
            foreach ($queues as $queue) {
                if (preg_match("/$inputFilter/", $queue)) {
                    $results[] = $queue;
                }
            }
        } else {
            $results = config('queue.queues');
        }

        if (count($results) === 0) {
            $this->error('No matching queue was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode(json_encode($results)), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Queue', 'Jobs'],
                array_map(function ($queue) {
                    return [
                        $queue,
                        Job::where('queue', '=', $queue)->count(),
                    ];
                }, $results)
            );
        }

        return $this->getSuccessExitCode();
    }
}
