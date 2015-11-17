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
use Lang;

class TicketsController extends Controller
{

    /*
     * Call the parent constructor to generate a base ACL
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
        $tickets = Ticket::all();

        return Datatables::of($tickets)
            // Create the action buttons
            ->addColumn(
                'actions',
                function ($ticket) {
                    $actions = ' <a href="tickets/' . $ticket->id .
                        '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> '.
                        trans('misc.button.show').'</a> ';

                    return $actions;
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
                    $ticket->firstEvent[0]->timestamp,
                    $ticket->lastEvent[0]->timestamp,
                    $ticket->events->count(),
                    $ticket->status_id,
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
        //only allowed in debug mode or something
        //$input = Input::all();
        //Ticket::create( $input );
        //
        //return Redirect::route('admin.tickets.index')->with('message', 'Ticket has been created');
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
        //only allowed in debug mode or something
        //return view('tickets.edit')->with('ticket', $ticket);
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
        $note->submitter = Lang::get('ash.communication.abusedesk'). $postingUser;
        $note->text = Input::get('text');
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
        //only allowed in debug mode or something
        //$ticket->delete();
        //
        //return Redirect::route('admin.tickets.index')->with('message', 'Ticket has been deleted.');
    }
}
