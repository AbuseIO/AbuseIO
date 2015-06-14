<?php

namespace AbuseIO\Commands;

use AbuseIO\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Netblock;
use AbuseIO\Models\Domain;
use AbuseIO\Models\Evidence;
use DB;

class EventsSave extends Command implements SelfHandling
{

    public $events;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($events)
    {

        $this->events = $events;

    }

    /**
     * Execute the command.
     *
     * @return array
     */
    public function handle()
    {

        foreach ($this->events as $event) {

            // Here we will thru all the events and look if these is an existing ticket. We will split them up into
            // two seperate arrays: $eventsNew and $events$known. We can save all the known events in the DB with
            // a single event saving loads of queries.

            /*
                'ip',
                'domain',
                'class_id',
                'type_id',
                'ip_contact_reference',
                'ip_contact_name',
                'ip_contact_email',
                'ip_contact_rpchost',
                'ip_contact_rpckey',
                'domain_contact_reference',
                'domain_contact_name',
                'domain_contact_email',
                'domain_contact_rpchost',
                'domain_contact_rpckey',
                'status_id',
                'auto_notify',
                'notified_count',
                'last_notify_count',
                'last_notify_timestamp'
             */

            $ipContact = [
                'reference'     => 'UNDEFIP',
                'name'          => 'Undefined contact IP',
                'email'         => 'undefip@isp.local',
                'rpc_host'      => '',
                'rpc_key'       => '',
                'auto_notify'   => '0',
                'enabled'       => '1',
                ];

            $domainContact = [
                'reference'     => 'UNDEFDOMAIN',
                'name'          => 'Undefined contact Domain',
                'email'         => 'undefdomain@isp.local',
                'rpc_host'      => '',
                'rpc_key'       => '',
                'auto_notify'   => '0',
                'enabled'       => '1',
            ];


            $search = Ticket::
                where('ip', '=', $event['ip'])
                ->where('class_id', '=', $event['class'], 'AND')
                ->where('type_id', '=', $event['type'], 'AND')
                ->where('ip_contact_reference', '=', $ipContact['reference'], 'AND')
                ->where('domain_contact_reference', '=', $domainContact['reference'], 'AND')
                ->where('status_id', '!=', 2, 'AND')
                ->get();



            if ($search->count() === 0) {

                $ticket = new Ticket();
                $ticket->save;

            } elseif ($search->count() === 1) {

                // This is an existing ticket

            } else $this->failed('Unable to link to ticket, multiple open tickets found for same event type');

        }

        $this->success('');

    }

}
