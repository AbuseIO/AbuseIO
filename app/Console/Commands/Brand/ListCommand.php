<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Brand;

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
    protected $headers = ['Id', 'Name', 'Company name'];

    /**
     * {@inheritdoc}.
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $brand  \AbuseIO\Models\Brand|null */
        foreach ($list as $brand) {
            $result[] = [$brand->id, $brand->name, $brand->company_name];
        }

        return $result;
    }

    /**
     * {@inheritdoc}.
     */
    protected function findWithCondition($filter)
    {
        return Brand::where('name', 'like', "%{$filter}%")->get();
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        return Brand::all();
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'brand';
    }
}
