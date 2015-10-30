<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\TicketsFormRequest;
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
     * Display all tickets
     * @return Response
     */
    public function index()
    {
        // When we came from the search page, create a Query
        $tickets = Ticket::where(
            function ($query) {
                if (!empty(Input::get('ticket_id'))) {
                    $query->where('id', Input::get('ticket_id'));
                }
                if (!empty(Input::get('ip_address'))) {
                    $query->where('ip', Input::get('ip_address'));
                }
                if (!empty(Input::get('customer_code'))) {
                    $query->where('ip_contact_reference', Input::get('customer_code'));
                }
                if (!empty(Input::get('customer_name'))) {
                    $query->where('ip_contact_name', 'like', '%'.Input::get('customer_name').'%');
                }
                if (Input::get('classification') > 0) {
                    $query->where('class_id', Input::get('classification'));
                }
                if (Input::get('type') > 0) {
                    $query->where('type_id', Input::get('type'));
                }
                if (Input::get('status') > 0) {
                    $query->where('status_id', Input::get('status'));
                }
                if (Input::get('state') > 0) {
                    switch (Input::get('state')) {
                        case 1:
                            // Notified
                            $query->where('notified_count', '>=', 1);
                            break;
                        case 2:
                            // Not notified
                        default:
                            $query->where('notified_count', 0);
                            break;
                    }
                }
            }
        )->paginate(10);

        return view('tickets.index')
            ->with('tickets', $tickets)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Display all open tickets
     * @return Response
     */
    public function statusOpen()
    {
        $tickets = Ticket::where('status_id', 1)->paginate(10);
        return view('tickets.index')
            ->with('tickets', $tickets)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Display all closed tickets
     * @param Request $request
     * @return Response
     */
    public function statusClosed()
    {
        $tickets = Ticket::where('status_id', 2)->paginate(10);
        return view('tickets.index')
            ->with('tickets', $tickets)
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
    public function export()
    {

        $tickets = Ticket::all();
        $columns = [
            'id' => 'Ticket ID',
        ];

        $output = '"' . implode('", "', $columns) . '"' . PHP_EOL;

        foreach ($tickets as $ticket) {
            $row = [
                $ticket->id,
            ];

            $output .= '"' . implode('", "', $row) . '"' . PHP_EOL;
        }

        return response(substr($output, 0, -1), 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="Tickets.csv"');
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
