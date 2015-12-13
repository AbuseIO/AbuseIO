<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\TicketsFormRequest;
use yajra\Datatables\Datatables;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Note;
use Redirect;
use Input;

class TicketsController extends Controller
{

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
        $tickets = Ticket::all();

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
            ->addColumn(
                'event_count',
                function ($ticket) {
                    return $ticket->events->count();
                }
            )
            ->addColumn(
                'notes_count',
                function ($ticket) {
                    return $ticket->unreadNotes->count();
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
     * @return Response
     */
    public function index()
    {
        return view('tickets.index')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a ticket
     * @param Request $request
     * @return Response
     */
    public function create()
    {

        return view('tickets.create')
            ->with('auth_user', $this->auth_user);

    }

    /**
     * Export tickets to CSV format.
     * @return Response
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
     * @return Response
     */
    public function store(TicketsFormRequest $ticket)
    {
        // Todo, implement new ticket
    }

    /**
     * Display the specified ticket.
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     * @internal param int $id
     */
    public function show(Ticket $ticket)
    {

        return view('tickets.show')
            ->with('ticket', $ticket)
            ->with('auth_user', $this->auth_user);

    }

    /**
     * Show the form for editing the specified ticket.
     * @param  int  $id
     * @return Response
     */
    public function edit(Ticket $ticket)
    {
        // We don't allow tickets to be editted directly (only subfunctions like contactUpdate)
    }

    /**
     * Update the specified ticket in storage.
     * @param  int  $id
     * @return Response
     */
    public function update(Ticket $ticket)
    {
        if (config('main.notes.show_abusedesk_names') === true) {
            $postingUser = ' (' . $this->auth_user->fullName() . ')';
        } else {
            $postingUser = '';
        }

        $note = new Note;
        $note->ticket_id = $ticket->id;
        $note->submitter = trans('ash.communication.abusedesk'). $postingUser;
        $note->text = Input::get('text');
        $note->hidden = empty(Input::get('hidden')) ? false : true;
        $note->viewed = true;
        $note->save();

        return Redirect::route('admin.tickets.show', $ticket->id)->with('message', 'Ticket has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return Response
     */
    public function destroy(Ticket $ticket)
    {
        // We don't allow tickets to be deleted
    }

    /**
     * Updates the ticket to the given status.
     * @param Ticket $ticket
     * @return Response
     */
    public function status(Ticket $ticket)
    {
        // TODO: (mark) Maybe use existing update() for this?
        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', '{PLACEHOLDER} Ticket status has been updated.');
    }

    /**
     * Send a notification for this ticket.
     * @param Ticket $ticket
     * @return Response
     */
    public function notify(Ticket $ticket)
    {
        // TODO: (mark) Or are we going to call the notification functions directly?
        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', '{PLACEHOLDER} Ticket contact(s) notified.');
    }

    /**
     * Updates the requested contact information.
     * @param Ticket $ticket
     * @return Response
     */
    public function updatecontact(Ticket $ticket)
    {
        // TODO: (mark) Maybe use existing update() for this?
        return Redirect::route(
            'admin.tickets.show',
            $ticket->id
        )->with('message', '{PLACEHOLDER} Ticket contact(s) updated.');
    }
}
