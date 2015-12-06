<?php

namespace AbuseIO\Http\Controllers;

use Request;
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
        $brand = false;
        $ticket = Ticket::find($ticketID);
        $AshAuthorisedBy = Request::instance()->query('AshAuthorisedBy');

        if ($AshAuthorisedBy == 'TokenIP') {
            $brand = $ticket->accountIp->brand;
        }
        if ($AshAuthorisedBy == 'TokenDomain') {
            $brand = $ticket->accountDomain->brand;
        }

        if (empty($brand)) {
            abort(500);
        }

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

        $note = new Note();
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
