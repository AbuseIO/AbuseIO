<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Models\Netblock;
use Illuminate\Console\Command;

class ListNetblock extends Command
{
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netblock:list {--filter= : Filter netblocks by first ip}
                                          {--json : Output the netblocks in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all netblocks or listed netblocks based on filter';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputFilter = $this->option('filter');
        $netblockSelectQuery = Netblock::select('id', 'contact_id', 'first_ip', 'last_ip');

        if ($inputFilter) {
            $netblocks = $netblockSelectQuery->where('first_ip', 'like', "%{$inputFilter}%")->get();
        } else {
            $netblocks = $netblockSelectQuery->get();
        }

        if(empty($netblocks) || $netblocks->count() === 0) {
            $this->info('No netblocks found.');
            return $this->getNotFoundExitCode();
        }

        if ($this->option('json')) {
            /* the juggling from and to json is a way of ensuring pretty_print */
            $this->output->write(json_encode(json_decode($netblocks->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table(
                ['Id', 'Contact', 'First IP', 'Last IP'],
                $netblocks->map(function ($netblock) {
                    return [
                        'Id' => $netblock->id,
                        'Contact' => $netblock->contact->name,
                        'First IP' => $netblock->first_ip,
                        'Last IP' => $netblock->last_ip,
                    ];
                })->toArray()
            );
        }

        return $this->getSuccessExitCode();
    }
}
