<?php

namespace AbuseIO\Console\Commands\Collector;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use Illuminate\Console\Command;

class ShowCollector extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collector:show {collector : Name of the collector to show}
                                           {--json : Output the collector in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a collector based on the name';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputCollectorArgument = $this->argument('collector');

        $inputCollectorFirstCharacterUppercase = ucfirst($inputCollectorArgument);

        $result = config("collectors.{$inputCollectorFirstCharacterUppercase}.collector");

        if (empty($result)) {
            $this->error('No matching collector was found.');
            return $this->getNotFoundExitCode();
        }

        $collector = collect([(object)$result])->first();

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode($collector, JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Name', $collector->name],
                    ['Description', $collector->description],
                    ['Enabled', castBoolToString($collector->enabled)],
                    ['Location', $collector->location],
                    ['Key', $collector->key]
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
