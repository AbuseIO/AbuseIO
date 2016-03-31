<?php

namespace AbuseIO\Console\Commands\Note;

use AbuseIO\Console\Commands\AbstractListCommand;
use AbuseIO\Models\Note;

/**
 * Class ListCommand.
 */
class ListCommand extends AbstractListCommand
{
    protected $filterArguments = ['id', 'submitter'];

    /**
     * The headers of the table.
     *
     * @var array
     */
    protected $headers = ['Id', 'Ticket id', 'Submitter', 'text', 'Hidden', 'Viewed'];

    /**
     * {@inheritdoc}.
     */
    protected function transformListToTableBody($list)
    {
        $result = [];
        /* @var $note  \AbuseIO\Models\Note|null */
        foreach ($list as $note) {
            $result[] = [
                $note->id,
                $note->ticket_id,
                $note->submitter,
                $note->text,
                $note->hidden,
                $note->viewed,
            ];
        }

        return $result;
    }

    /**
     * {@inheritdoc}.
     */
    protected function findWithCondition($filter)
    {
        return Note::where('id', $filter)
            ->orWhere('submitter', 'like', '%'.$filter.'%')
                ->get();
    }

    /**
     * {@inheritdoc}.
     */
    protected function findAll()
    {
        return Note::all();
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAsNoun()
    {
        return 'note';
    }
}
