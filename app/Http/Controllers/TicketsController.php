<?php namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Ticket;
use Input;
use Redirect;

use Illuminate\Http\Request;

class TicketsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $tickets = Ticket::with('contact')->paginate(10);

        return view('tickets.index')->with('tickets', $tickets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Export listing to CSV format.
     *
     * @return Response
     */
    public function export()
    {
        $tickets = Ticket::all();

        return view('tickets.export')->with('tickets', $tickets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        Ticket::create( $input );

        return Redirect::route('tickets.index')->with('message', 'Ticket has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show')->with('ticket', $ticket);
    }

    /**
     * Show the form for editing the specified resource.
     *
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
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Ticket $ticket)
    {
        //only allowed in debug mode or something
        //$input = array_except(Input::all(), '_method');
        //$ticket->update($input);
        //
        //return Redirect::route('tickets.show', $ticket->id)->with('message', 'Ticket has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Ticket $ticket)
    {
        //only allowed in debug mode or something
        //$ticket->delete();
        //
        //return Redirect::route('tickets.index')->with('message', 'Ticket has been deleted.');
    }

}
