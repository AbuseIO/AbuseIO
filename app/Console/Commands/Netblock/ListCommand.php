<?php

namespace AbuseIO\Console\Commands\Netblock;

use AbuseIO\Models\Netblock;
use Illuminate\Console\Command;
use Carbon;

class ListCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'netblock:list
                            {--filter= : Applies a filter on the first_ip }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available netblocks';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', 'Contact', 'First IP', 'Last IP'];

    /**
     * The fields of the table / database row
     * @var array
     */
    protected $fields = ['first_ip', 'last_ip']; // don't know how to do the field contact conform this syntax.

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
            $netblockList = Netblock::where('first_ip', 'like', "%{$this->option('filter')}%")->get();
        } else {
            $netblockList = Netblock::all();
        }
        $this->table(
            $this->headers,
            $this->transformNetblockListToTableBody($netblockList, $netblockList)
        );

        return true;
    }

    /**
     * @param $netblockList
     * @param $netblocks
     * @return array
     */
    public function transformNetblockListToTableBody($netblockList, $netblocks)
    {
        $netblocks = [];
        /* @var $netblock  \AbuseIO\Models\Netblock|null */
        foreach ($netblockList as $netblock) {
            $netblocks[] = [$netblock->id, $netblock->contact->name, $netblock->first_ip, $netblock->last_ip];
        }
        return $netblocks;
    }
}
