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
        $AshAuthorisedBy = Request::get('AshAuthorisedBy');

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
        $brand = false;
        $submittor = false;

        $ticket = Ticket::find($ticketID);
        $AshAuthorisedBy = Request::get('AshAuthorisedBy');

        if ($AshAuthorisedBy == 'TokenIP') {
            $brand = $ticket->accountIp->brand;
            $submittor = trans('ash.basic.ip') . ' ' . trans('ash.communication.contact');
        }
        if ($AshAuthorisedBy == 'TokenDomain') {
            $brand = $ticket->accountDomain->brand;
            $submittor = trans('ash.basic.domain') . ' ' . trans('ash.communication.contact');
        }

        if (empty($brand) || empty($submittor)) {
            //abort(500);
        }

        $text = Input::get('text');
        if (empty($text)) {
            $message = 'You cannot add an empty message!';
        } else {
            $message = 'Note has been added.';

            $note = new Note();
            $note->ticket_id = $ticket->id;
            $note->submitter = $submittor;
            $note->text = $text;
            $note->save();
        }

        return view('ash')
            ->with('brand', $brand)
            ->with('ticket', $ticket)
            ->with('token', $token)
            ->with('message', $message);
    }
}
