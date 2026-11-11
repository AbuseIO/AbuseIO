<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Domain;
use Illuminate\Console\Command;

class ListDomain extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:list {--filter= : Filter the domains by name}
                                        {--json : Output the domains in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all domains or listed domains based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputOptionFilter = $this->option('filter');
        $domains = Domain::where('name', 'like', "%{$inputOptionFilter}%")->get();

        if(empty($domains) || $domains->count() === 0) {
            $this->error('No domain found for given filter.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($domains->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Id', 'Contact', 'Name', 'Enabled'],
                $domains->map(function ($domain) {
                    return [
                        $domain->id,
                        $domain->contact->name,
                        $domain->name,
                        castStringToBool($domain->enabled),
                    ];
                })->toArray()
            );
        }

        return $this->getSuccessExitCode();
    }
}
