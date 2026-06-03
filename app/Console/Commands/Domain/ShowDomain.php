<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Domain;
use Illuminate\Console\Command;

class ShowDomain extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:show {domain : The ID or name of the domain}
                                        {--json : Output the domain in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a domain based on the provided ID or name';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputDomain = $this->argument('domain');
        $domain = Domain::where('id', $inputDomain)
            ->orWhere('name', $inputDomain)
            ->first();

        if (!$domain) {
            $this->error("No matching domain was found.");
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($domain->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Id', $domain->id],
                    ['Contact', $domain->contact ? ($domain->contact->reference . ' / ' . $domain->contact->email) : ''],
                    ['Name', $domain->name],
                    ['Enabled', $domain->enabled],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
