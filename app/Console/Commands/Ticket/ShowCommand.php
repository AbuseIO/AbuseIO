<?php

namespace AbuseIO\Console\Commands\Ticket;

use AbuseIO\Console\Commands\AbstractShowCommand;
use AbuseIO\Models\Ticket;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ShowCommand.
 */
class ShowCommand extends AbstractShowCommand
{
    /**
     * {@inherit docs}.
     */
    protected function getAsNoun()
    {
        return 'ticket';
    }

    /**
     * {@inherit docs}.
     */
    protected function getAllowedArguments()
    {
        return ['id'];
    }

    /**
     * {@inherit docs}.
     */
    protected function getFields()
    {
        return [
            'id',
            'ip',
            'domain',
            'class_id',
            'type_id',
            'ip_contact_account_id',
            'ip_contact_reference',
            'ip_contact_name',
            'ip_contact_email',
            'ip_contact_api_host',
            'ip_contact_auto_notify',
            'ip_contact_notified_count',
            'domain_contact_account_id',
            'domain_contact_reference',
            'domain_contact_name',
            'domain_contact_email',
            'domain_contact_api_host',
            'domain_contact_auto_notify',
            'domain_contact_notified_count',
            'status_id',
            'last_notify_count',
            'last_notify_timestamp',
        ];
    }

    /**
     * {@inherit docs}.
     */
    protected function getCollectionWithArguments()
    {
        return Ticket::where('id', $this->argument('ticket'));
    }

    /**
     * {@inherit docs}.
     */
    protected function defineInput()
    {
        return [
            new InputArgument(
                'ticket',
                InputArgument::REQUIRED,
                'Use the id for a ticket to show it.'
            ),
        ];
    }

    /**
     * {@inherit docs}.
     */
    protected function transformObjectToTableBody($model)
    {
        $result = parent::transformObjectToTableBody($model);
        $result[] = ['[Events ID]', '[Events Source]'];
        foreach ($model->events as $event) {
            $result[] = [$event->id, $event->source];
        }

        return $result;
    }
}
