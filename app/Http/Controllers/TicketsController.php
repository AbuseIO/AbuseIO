<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\TicketsFormRequest;
use yajra\Datatables\Datatables;
use AbuseIO\Models\Ticket;
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
                // Sp we use some nesting foo
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
     * Show the form for editing the specified ticket.
     *
     * @param  Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        // We don't allow tickets to be editted directly (only subfunctions like contactUpdate)
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
     * Remove the specified resource from storage.
     *
     * @param  Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        // We don't allow tickets to be deleted
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
     * Send a notification for this ticket.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notify(Ticket $ticket)
    {
        // TODO: #AIO-39 Interaction tickets - (mark) Or are we going to call the notification functions directly?
        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', '{PLACEHOLDER} Ticket contact(s) notified.');
    }

    /**
     * Updates the requested contact information.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatecontact(Ticket $ticket)
    {
        // TODO: #AIO-39 Interaction tickets - (mark) Maybe use existing update() for this?
        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', '{PLACEHOLDER} Ticket contact(s) updated.');
    }
}
