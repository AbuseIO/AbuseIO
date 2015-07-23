<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\TicketsFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Ticket;
use Redirect;
use Input;

class TicketsController extends Controller
{

    /**
     * Display all tickets
     * @return Response
     */
    public function index()
    {
        $tickets = Ticket::paginate(10);

        return view('tickets.index')
            ->with('tickets', $tickets);
    }

    /**
     * Display all open tickets
     * @return Response
     */
    public function statusOpen()
    {
        $tickets = Ticket::where('status_id', 1)->paginate(10);
        return view('tickets.index')
            ->with('tickets', $tickets);
    }

    /**
     * Display all closed tickets
     * @return Response
     */
    public function statusClosed()
    {
        $tickets = Ticket::where('status_id', 2)->paginate(10);
        return view('tickets.index')
            ->with('tickets', $tickets);
    }

    /**
     * Show the form for creating a ticket
     * @return Response
     */
    public function create()
    {

        return view('tickets.create');

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
     * @param  int  $id
     * @return Response
     */
    public function show(Ticket $ticket)
    {

        return view('tickets.show')
            ->with('ticket', $ticket);

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
     * Update the specified resource in storage.
     * @param  int  $id
     * @return Response
     */
    public function update(Ticket $ticket)
    {
        //only allowed in debug mode or something
        //$input = array_except(Input::all(), '_method');
        //$ticket->update($input);
        //
        //return Redirect::route('admin.tickets.show', $ticket->id)->with('message', 'Ticket has been updated.');
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
