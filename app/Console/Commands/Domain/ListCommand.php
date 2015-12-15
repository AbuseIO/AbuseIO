<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Models\Domain;
use Illuminate\Console\Command;
use Carbon;

class ListCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'domain:list
                            {--filter= : Applies a filter on the domain name }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available domains';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', 'Contact', 'Name', "Enabled"];

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {

        if (!empty($this->option('filter'))) {
            $domainList = Domain::where('name', 'like', "%{$this->option('filter')}%")->get();
        } else {
            $domainList = Domain::all();
        }

        if (count($domainList) === 0) {
            $this->error("No domains found for given filter.");
        } else {
            $this->table(
                $this->headers,
                $this->transformDomainListToTableBody($domainList)
            );
        }
        return true;
    }

    /**
     * @param $netblockList
     * @param $netblocks
     * @return array
     */
    public function transformDomainListToTableBody($domainList)
    {
        $result = [];
        /* @var $domain  \AbuseIO\Models\Domain|null */
        foreach ($domainList as $domain) {
            $result[] = [$domain->id, $domain->contact->name, $domain->name, $domain->enabled];
        }
        return $result;
    }
}
