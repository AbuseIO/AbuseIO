<?php

namespace AbuseIO\Console\Commands\Info;

use AbuseIO\Console\Commands\ExitCodeHooks;
use Illuminate\Console\Command;

class ListTypes extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'info:list-types {--filter= : A filter to apply to the types list}
                                            {--json : Output the types list in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists the types available in the system, optionally filtered by a search term';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputFilter = $this->option('filter');
        $tagList = [];
        foreach (trans('types.type') as $tag => $typeInfo) {
            if ($inputFilter !== null) {
                $matchesFilter = preg_match("/{$inputFilter}/i", $tag)
                    || preg_match("/{$inputFilter}/i", $typeInfo['name']);

                if (!$matchesFilter) {
                    continue;
                }
            }

            $tagList[$tag] = [
                $tag,
                $typeInfo['name'],
            ];
        }

        if (empty($tagList)) {
            $this->error('No types found matching the filter criteria.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode($tagList, JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Type', 'Description'],
                $tagList
            );
        }

        return $this->getSuccessExitCode();
    }
}
