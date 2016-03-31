<?php

namespace AbuseIO\Console\Commands\Ticket;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Ticket;

/**
 * Class ListCommand.
 */
class ListCommand extends AbstractListCommand
{
    protected $filterArguments = ['id'];
    /**
     * The headers of the table.
     *
     * @var array
     */
    protected $headers = ['Id', 'Ip', 'Domain', 'Class id', 'Type id'];

    /**
     * {@inheritdoc}.
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $ticket  \AbuseIO\Models\Ticket|null */
        foreach ($list as $ticket) {
            $result[] = [
                $ticket->id,
                $ticket->ip,
                $ticket->domain,
                $ticket->class_id,
                $ticket->type_id,
            ];
        }

        return $result;
    }

    /**
     * {@inheritdoc}.
     */
    protected function findWithCondition($filter)
    {
        return Ticket::where('id', $filter)
                ->get();
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        return Ticket::all();
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'ticket';
    }
}
