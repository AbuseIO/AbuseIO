<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\TicketsFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Note;
use Redirect;
use Input;

class TicketsController extends Controller
{

    /*
     * Call the parent constructor to generate a base ACL
     */
    public function __construct()
    {
        parent::__construct('createDynamicACL');
    }

    /**
     * Display all tickets
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tickets = Ticket::paginate(10);

        return view('tickets.index')
            ->with('tickets', $tickets)
            ->with('user', $request->user());
    }

    /**
     * Display all open tickets
     * @param Request $request
     * @return Response
     */
    public function statusOpen(Request $request)
    {
        $tickets = Ticket::where('status_id', 1)->paginate(10);
        return view('tickets.index')
            ->with('tickets', $tickets)
            ->with('user', $request->user());
    }

    /**
     * Display all closed tickets
     * @param Request $request
     * @return Response
     */
    public function statusClosed(Request $request)
    {
        $tickets = Ticket::where('status_id', 2)->paginate(10);
        return view('tickets.index')
            ->with('tickets', $tickets)
            ->with('user', $request->user());
    }

    /**
     * Show the form for creating a ticket
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {

        return view('tickets.create')
            ->with('user', $request->user());

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
    public function show(Request $request, Ticket $ticket)
    {

        return view('tickets.show')
            ->with('ticket', $ticket)
            ->with('user', $request->user());

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
        $note = new Note;
        $note->ticket_id = $ticket->id;
        $note->submitter = 'abusedesk';
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
