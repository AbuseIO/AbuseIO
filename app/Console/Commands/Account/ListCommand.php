<?php

namespace AbuseIO\Console\Commands\Account;


use AbuseIO\Models\Account;
use AbuseIO\Console\Commands\AbstractListCommand;

class ListCommand extends AbstractListCommand
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
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return Account::all();
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Account::where('name', 'like', "%{$filter}%")->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $account  \AbuseIO\Models\Account|null */
        foreach ($list as $account) {
            $result[] = [$account->id, $account->name, $account->brand->name,  castBoolToString($account->disabled)];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "account";
    }
}

