<?php

namespace AbuseIO\Console\Commands\Ticket;

use AbuseIO\Console\Commands\ExitCodeHooks;
use AbuseIO\Console\Commands\ShowHelpWhenRunTimeExceptionOccurs;
use AbuseIO\Models\Ticket;
use Illuminate\Console\Command;

class ShowTicket extends Command
{
    use ShowHelpWhenRunTimeExceptionOccurs;
    use ExitCodeHooks;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:show {ticket : The ID of the ticket}
                                        {--json : Output the ticket in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a ticket based on the provided ID';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputArgumentTicket = $this->argument('ticket');
        $ticket = Ticket::where('id', $inputArgumentTicket)->first();

        if (!$ticket) {
            $this->error("No matching ticket was found.");
            return $this->getNotFoundExitCode();
        }

        $tableData = [
            ['Id', $ticket->id],
            ['Ip', $ticket->ip],
            ['Domain', $ticket->domain],
            ['Class id', $ticket->class_id],
            ['Type id', $ticket->type_id],
            ['Status id', $ticket->status_id],
            ['Ip contact account id', $ticket->ip_contact_account_id],
            ['Ip contact reference', $ticket->ip_contact_reference],
            ['Ip contact name', $ticket->ip_contact_name],
            ['Ip contact email', $ticket->ip_contact_email],
            ['Ip contact api host', $ticket->ip_contact_api_host],
            ['Ip contact auto notify', $ticket->ip_contact_auto_notify],
            ['Ip contact notified count', $ticket->ip_contact_notified_count],
            ['Domain contact account id', $ticket->domain_contact_account_id],
            ['Domain contact reference', $ticket->domain_contact_reference],
            ['Domain contact name', $ticket->domain_contact_name],
            ['Domain contact email', $ticket->domain_contact_email],
            ['Domain contact api host', $ticket->domain_contact_api_host],
            ['Domain contact auto notify', $ticket->domain_contact_auto_notify],
            ['Domain contact notified count', $ticket->domain_contact_notified_count],
            ['Status id', $ticket->status_id],
            ['Last notify count', $ticket->last_notify_count],
            ['Last notify timestamp', $ticket->last_notify_timestamp],
        ];

        $tableData[] = ['[Events ID]', '[Events Source]'];

        foreach ($ticket->events as $event) {
            $tableData[] = [$event->id, $event->source];
        }

        if ($this->option('json')) {
            $this->output->write(json_encode(json_decode($ticket->toJson()), JSON_PRETTY_PRINT));
        } else {
            $this->table([], $tableData);
        }

        return $this->getSuccessExitCode();
    }
}
