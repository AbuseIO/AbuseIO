<?php namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Note;
use AbuseIO\Models\Event;
use Input;
use Redirect;

class AshController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($ticketID, $token)
    {

        $ticket = Ticket::find($ticketID);
        // 6bb1aef09ea536260e3afe3fb9b432e4
        // c1eee3ce87f1fd774eb8819c820fa5be

        $validTokenIP       = md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference);
        $validTokenDomain   = md5($ticket->id . $ticket->ip . $ticket->domain_contact_reference);

        if ($token == $validTokenIP || $token == $validTokenDomain) {

            return view('ash')
                ->with('ticket', $ticket);

        } else {

            return view('errors.403');

        }

    }
}
