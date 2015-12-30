<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\TicketsFormRequest;
use AbuseIO\Jobs\Notification;
use AbuseIO\Jobs\TicketUpdate;
use AbuseIO\Models\Ticket;
use yajra\Datatables\Datatables;
use Redirect;
use DB;

/**
 * Class TicketsController
 * @package AbuseIO\Http\Controllers
 */
class TicketsController extends Controller
{

    /**
     * TicketsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        //$tickets = Ticket::all();
        $tickets = Ticket::select(
            "tickets.*",
            DB::raw("count(distinct events.id) as event_count"),
            DB::raw("count(distinct notes.id) as notes_count")
        )
            ->leftJoin('events', 'events.ticket_id', '=', 'tickets.id')
            ->leftJoin(
                'notes',
                function ($join) {
                // We need a LEFT JOIN .. ON .. AND ..).
                // This doesn't exist within Illuminate's JoinClause class
                // So we use some nesting foo here
                    $join->on('notes.ticket_id', '=', 'tickets.id')
                        ->nest(
                            function ($join) {
                                $join->on('notes.viewed', '=', DB::raw("'false'"));
                            }
                        );
                }
            )
            ->groupBy('tickets.id');

        return Datatables::of($tickets)
            // Create the action buttons
            ->addColumn(
                'actions',
                function ($ticket) {
                    $actions = ' <a href="tickets/' . $ticket->id .
                        '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-eye-open"></span> '.
                        trans('misc.button.show').'</a> ';

                    return $actions;
                }
            )
            ->editColumn(
                'type_id',
                function ($ticket) {
                    return trans('types.type.' . $ticket->type_id . '.name');
                }
            )
            ->editColumn(
                'class_id',
                function ($ticket) {
                    return trans('classifications.' . $ticket->class_id . '.name');
                }
            )
            ->editColumn(
                'status_id',
                function ($ticket) {
                    return trans('types.status.' . $ticket->status_id . '.name');
                }
            )
            ->make(true);
    }

    /**
     * Display all tickets
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tickets.index')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a ticket
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: #AIO-39 Interaction tickets - (bart) implement new ticket by adding events(data)?

        return view('tickets.create')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Export tickets to CSV format.
     *
     * @param string $format
     * @return \Illuminate\Http\Response
     */
    public function export($format)
    {
        // TODO #AIO-?? ExportProvider - (mark) Move this into an ExportProvider or something s?
        $tickets = Ticket::all();

        if ($format === 'csv') {
            $columns = [
                'id'            => 'Ticket ID',
                'ip'            => 'IP address',
                'class_id'      => 'Classification',
                'type_id'       => 'Type',
                'first_seen'    => 'First seen',
                'last_seen'     => 'Last seen',
                'event_count'   => 'Events',
                'status_id'     => 'Ticket Status',
            ];

            $output = '"' . implode('", "', $columns) . '"' . PHP_EOL;

            foreach ($tickets as $ticket) {
                $row = [
                    $ticket->id,
                    $ticket->ip,
                    $ticket->class_id,
                    $ticket->type_id,
                    $ticket->firstEvent[0]->seen,
                    $ticket->lastEvent[0]->seen,
                    $ticket->events->count(),
                    trans('types.status.'.$ticket->status_id.'.name'),
                ];

                $output .= '"' . implode('", "', $row) . '"' . PHP_EOL;
            }

            return response(substr($output, 0, -1), 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="Tickets.csv"');
        }

        return Redirect::route('admin.contacts.index')
            ->with('message', "The requested format {$format} is not available for exports");
    }

    /**
     * Store a newly created ticket in storage.
     *
     * @param TicketsFormRequest $ticket
     * @return \Illuminate\Http\Response
     */
    public function store(TicketsFormRequest $ticket)
    {
        // TODO: #AIO-39 Interaction tickets - (bart) implement new ticket by adding events(data)?
    }

    /**
     * Display the specified ticket.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show')
            ->with('ticket', $ticket)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the specified ticket in storage.
     *
     * @param  Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Ticket $ticket)
    {
        //return Redirect::route('admin.tickets.show', $ticket->id)->with('message', 'Ticket has been updated.');
    }

    /**
     * Updates the ticket to the given status.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status(Ticket $ticket)
    {
        // TODO: #AIO-39 Interaction tickets - (mark) Maybe use existing update() for this?
        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', '{PLACEHOLDER} Ticket status has been updated.');
    }

    /**
     * Send a notification for this ticket to the IP contact.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifyIpContact(Ticket $ticket)
    {
        $notification = new Notification;
        $notification->walkList(
            $notification->buildList($ticket->id, false, true, 'ip')
        );

        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', 'IP Contact has been notified.');
    }

    /**
     * Send a notification for this ticket to the domain contact.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifyDomainContact(Ticket $ticket)
    {
        $notification = new Notification;
        $notification->walkList(
            $notification->buildList($ticket->id, false, true, 'domain')
        );

        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', 'Domain Contact has been notified.');
    }

    /**
     * Send a notification for this ticket to both contacts.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifyBothContacts(Ticket $ticket)
    {
        $notification = new Notification;
        $notification->walkList(
            $notification->buildList($ticket->id, false, true)
        );

        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', 'IP and Domain Contact have been notified.');
    }

    /**
     * Updates the requested IP contact information.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateIpContact(Ticket $ticket)
    {
        TicketUpdate::ipContact($ticket);

        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', 'IP Contact has been updated.');
    }

    /**
     * Updates the requested domain contact information.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDomainContact(Ticket $ticket)
    {
        TicketUpdate::domainContact($ticket);

        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', 'Domain Contact has been updated.');
    }

    /**
     * Updates the requested contacts information.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBothContacts(Ticket $ticket)
    {
        TicketUpdate::ipContact($ticket);
        TicketUpdate::domainContact($ticket);

        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', 'IP and Domain Contact has been updated.');
    }
}
