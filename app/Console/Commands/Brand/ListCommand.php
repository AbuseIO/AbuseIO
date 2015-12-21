<?php

namespace AbuseIO\Console\Commands\Brand;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Brand;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Brand
 */
class ListCommand extends AbstractListCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'brand:list
                            {--filter= : Applies a filter on the brand name }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available brands';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', 'Name', 'Company name'];

    /**
     * {@inheritdoc }
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
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Brand::where('name', 'like', "%{$filter}%")->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return Brand::all();
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "brand";
    }
}

