<?php

namespace AbuseIO\Console\Commands\Collector;

use AbuseIO\Collectors\Factory as CollectorFactory;
use AbuseIO\Console\Commands\ExitCodeHooks;
use Illuminate\Console\Command;

class ListCollector extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collector:list {--filter= : Filters collectors on name}
                                           {--json : Output the list in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List collectors available in the system, optionally filtered by name';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputFilter = $this->option('filter');

        if ($inputFilter) {
            $collectors = preg_grep(
                "/" . preg_quote($inputFilter, '/') . "/i",
                CollectorFactory::getCollectors()
            );
            $tableData = array_map(
                function ($collector) {
                    return [
                        'Name' => $collector,
                        'Description' => config("collectors.{$collector}.collector.description"),
                    ];
                },
                $collectors
            );
        } else {
            $collectors = config('collectors');
            $tableData = array_map(
                function ($key, $collector) {
                    return [
                        'Name' => $key,
                        'Description' => $collector['collector']['description'],
                    ];
                },
                array_keys($collectors),
                $collectors
            );
        }

        if (empty($tableData)) {
            $this->info('No collectors found matching the filter.');
            return $this->getNotFoundExitCode();
        }
        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode($collectors, JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Name', 'Description'],
                $tableData
            );
        }

        return $this->getSuccessExitCode();
    }
}
