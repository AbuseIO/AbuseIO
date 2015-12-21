<?php

namespace AbuseIO\Console\Commands\Domain;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Domain;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Domain
 */
class ListCommand extends AbstractListCommand
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
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $domain  \AbuseIO\Models\Domain|null */
        foreach ($list as $domain) {
            $result[] = [$domain->id, $domain->contact->name, $domain->name, $domain->enabled];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Domain::where('name', 'like', "%{$this->option('filter')}%")->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return Domain::all();
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "domain";
    }
}
