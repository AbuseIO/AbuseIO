<?php

namespace AbuseIO\Console\Commands\Event;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Event;

/**
 * Class ListCommand
 * @package AbuseIO\Console\Commands\Event
 */
class ListCommand extends AbstractListCommand
{

    protected $filterArguments =["source"];
    /**
     * The headers of the table
     * @var array
     */
    protected $headers = ['Id', "Source", "Ticket id", "Information"];

    /**
     * {@inheritdoc }
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $event  \AbuseIO\Models\Event|null */
        foreach ($list as $event) {
            $result[] = [$event->id, $event->source, $event->ticket_id, $event->information];
        }
        return $result;
    }

    /**
     * {@inheritdoc }
     */
    protected function findWithCondition($filter)
    {
        return Event::where('source', 'like', "%{$filter}%")->get();
    }

    /**
     * {@inheritdoc }
     */
    protected function findAll()
    {
        return Event::all();
    }

    /**
     * {@inheritdoc }
     */
    protected function getAsNoun()
    {
        return "event";
    }
}

