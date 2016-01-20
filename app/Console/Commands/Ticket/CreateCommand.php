<?php

namespace AbuseIO\Console\Commands\Ticket;

use AbuseIO\Console\Commands\AbstractCreateCommand;
use AbuseIO\Models\Contact;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Ticket;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Validator;

class CreateCommand extends AbstractCreateCommand
{
    // TODO don't know how a ticket works.
    public function getArgumentsList()
    {
        return new InputDefinition([
            new InputArgument('ip', null, 'Ip'),
            new InputArgument('domain_id', null, 'ID from domain of which to copy the properties'),
            new InputArgument('class_id', null, 'Class id'),
            new InputArgument('type_id', null, 'Type id'),
            new InputArgument('contact_id', null, 'ID from contact of which to copy the properties'),
            new InputArgument('status_id', null, 'Status id'),

        ]);
    }

    public function getAsNoun()
    {
        return 'ticket';
    }

    protected function getModelFromRequest()
    {
        $ticket = new Ticket();

        /** @var Domain $domain */
        $domain = Domain::find($this->argument('domain_id'));
        /** @var Contact $contact */
        $contact = Contact::find($this->argument('contact_id'));

        $ticket->ip = $this->argument('ip');
        $ticket->domain = $domain->name;
        $ticket->class_id = $this->argument('class_id');
        $ticket->type_id = $this->argument('type_id');

        $ticket->ip_contact_account_id = $contact->account_id;
        $ticket->ip_contact_reference = $contact->reference;
        $ticket->ip_contact_name = $contact->name;
        $ticket->ip_contact_api_host = $contact->api_host;
        $ticket->ip_contact_auto_notify = $contact->auto_notify;
        //TODO don't know the purpose of this field;
        $ticket->ip_contact_notified_count = 0;

        $ticket->domain_contact_account_id = $contact->account_id;
        $ticket->domain_contact_reference = $contact->reference;
        $ticket->domain_contact_name = $contact->name;
        $ticket->domain_contact_email = $contact->email;
        $ticket->domain_contact_api_host = $contact->api_host;
        $ticket->domain_contact_auto_notify = $contact->auto_notify;
        //TODO don't know the purpose of this field;
        $ticket->domain_contact_notified_count = 0;

        $ticket->status_id = $this->argument('status_id');

        $ticket->last_notify_count = 0;
        $ticket->last_notify_timestamp = time();



        return $ticket;
    }

    protected function getValidator($model)
    {
        return Validator::make($model->toArray(), Ticket::createRules($model));
    }
}
