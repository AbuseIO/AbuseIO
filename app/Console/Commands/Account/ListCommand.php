<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Models\Account;
use Illuminate\Console\Command;
use Carbon;

class ListCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'account:list
                            {--filter= : Applies a filter on the account name }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available account';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', 'Name', 'Brand', "Disabled"];

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
            $accountList = Account::where('name', 'like', "%{$this->option('filter')}%")->get();
        } else {
            $accountList = Account::all();
        }

        if (count($accountList) === 0) {
            $this->error("No account found for given filter.");
        } else {
            $this->table(
                $this->headers,
                $this->transformAccountListToTableBody($accountList)
            );
        }
        return true;
    }

    /**
     * @param array $accountList
     * @return array
     */
    public function transformAccountListToTableBody($accountList)
    {
        $result = [];
        /* @var $account  \AbuseIO\Models\Account|null */
        foreach ($accountList as $account) {
            $result[] = [$account->id, $account->name, $account->brand->name,  castBoolToString($account->disabled)];
        }
        return $result;
    }
}
