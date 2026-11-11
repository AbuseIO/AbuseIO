<?php

namespace AbuseIO\Console\Commands\Info;

use AbuseIO\Console\Commands\ExitCodeHooks;
use Illuminate\Console\Command;

class ListClasses extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'info:list-classes {--filter= : Applies a filter on the classes}
                                              {--json : Output result as JSON}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all classes available in the system, optionally filtered by name, tag or alias';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputFilter = $this->option('filter');
        $taglist = [];
        foreach (trans('classifications') as $tag => $classInfo) {
            $aliases = isset($classInfo['aliases']) && is_array($classInfo['aliases'])
                ? implode(', ', $classInfo['aliases'])
                : '';

            if ($inputFilter) {
                $matchesFilter = preg_match("/{$inputFilter}/i", $tag)
                    || preg_match("/{$inputFilter}/i", $classInfo['name'])
                    || ($aliases !== '' && preg_match("/{$inputFilter}/i", $aliases));

                if (!$matchesFilter) {
                    continue;
                }
            }

            $taglist[$tag] = [
                $tag,
                $classInfo['name'],
                $aliases,
            ];
        }

        if (empty($taglist)) {
            $this->error('No classes found matching the filter criteria.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            $this->output->write(json_encode($taglist, JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Tag', 'Name', 'Aliasses'],
                $taglist
            );
        }

        return $this->getSuccessExitCode();
    }
}
