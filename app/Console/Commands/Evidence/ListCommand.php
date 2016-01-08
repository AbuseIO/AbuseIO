<?php

namespace AbuseIO\Console\Commands\Evidence;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Evidence;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Evidence
 */
class ListCommand extends AbstractListCommand
{

    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'evidence:list
                            {--filter= : Applies a filter on the evidence id or sender }
    ';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Shows a list of all available evidence';

    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', "Filename", "Sender", "Subject", "Created at", "Updated at", "Deleted at"];

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $evidence  \AbuseIO\Models\Evidence|null */
        foreach ($list as $evidence) {
            $result[] = [$evidence->id, $evidence->filename, $evidence->sender, $evidence->subject, $evidence->created_at, $evidence->updated_at, $evidence->deleted_at];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Evidence::where('id',  $filter)
                ->orWhere("sender", "like", '%'.$filter.'%')
                ->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return Evidence::all();
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "evidence";
    }
}

