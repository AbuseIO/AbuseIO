<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Netblock;
use Illuminate\Console\Command;

class ShowNetblock extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netblock:show {netblock : Use the firstIp, lastIp or contact name for a netblock to show it.}
                                          {--json : Output the netblock in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows the details of a netblock based on the first IP, last IP or contact name.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentNetblock = $this->argument('netblock');
        $netblock = Netblock::select('first_ip', 'last_ip', 'description', 'enabled', 'contact_id')
            ->where('first_ip', $inputArgumentNetblock)
            ->orWhere('last_ip', $inputArgumentNetblock)
            ->orWhereHas('contact', function ($query) use ($inputArgumentNetblock) {
                $query->where('name', $inputArgumentNetblock);
            })
            ->first();

        if (!$netblock) {
            $this->info('No matching netblock was found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($netblock->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                [],
                [
                    ['Contact', $netblock->contact->name],
                    ['First IP', $netblock->first_ip],
                    ['Last IP', $netblock->last_ip],
                    ['Description', $netblock->description],
                    ['Enabled', castBoolToString($netblock->enabled)],
                ]
            );
        }

        return $this->getSuccessExitCode();
    }
}
