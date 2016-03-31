<?php

namespace AbuseIO\Console\Commands\Account;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Account;

/**
 * Class ListCommand.
 */
class ListCommand extends AbstractListCommand
{
    protected $filterArguments = ['name'];

    /**
     * The headers of the table.
     *
     * @var array
     */
    protected $headers = ['Id', 'Name', 'Brand', 'Disabled'];

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        return Account::all();
    }

    /**
     * {@inheritdoc}.
     */
    protected function findWithCondition($filter)
    {
        return Account::where('name', 'like', "%{$filter}%")->get();
    }

    /**
     * {@inheritdoc}.
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
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'account';
    }
}
