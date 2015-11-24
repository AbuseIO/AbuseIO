<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Note;
use Input;

class AshController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($ticketID, $token)
    {
        $ticket = Ticket::find($ticketID);
        $brand = $ticket->account->brand;

        return view('ash')
            ->with('brand', $brand)
            ->with('ticket', $ticket)
            ->with('token', $token);

    }

    public function addNote($ticketID, $token)
    {
        $ticket = Ticket::find($ticketID);
        $brand = $ticket->account->brand;

        $text = Input::get('text');
        if (empty($text)) {

            return view('ash')
                ->with('brand', $brand)
                ->with('ticket', $ticket)
                ->with('token', $token)
                ->with('message', 'You cannot add an empty message!');
        }

        $note = new Note;
        $note->ticket_id = $ticket->id;
        $note->submitter = trans('ash.communication.contact');
        $note->text = $text;
        $note->save();

        return view('ash')
            ->with('brand', $brand)
            ->with('ticket', $ticket)
            ->with('token', $token)
            ->with('message', 'Ticket has been updated.');

    }
}
