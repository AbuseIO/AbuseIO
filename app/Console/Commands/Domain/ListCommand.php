<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Domain;

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
    protected $headers = ['Id', 'Contact', 'Name', 'Enabled'];

    /**
     * {@inheritdoc}.
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $domain  \AbuseIO\Models\Domain */
        foreach ($list as $domain) {
            $result[] = [$domain->id, $domain->contact->name, $domain->name, $domain->enabled];
        }

        return $result;
    }

    /**
     * {@inheritdoc}.
     */
    protected function findWithCondition($filter)
    {
        return Domain::where('name', 'like', "%{$this->option('filter')}%")->get();
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        return Domain::all();
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'domain';
    }
}
