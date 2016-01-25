<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Models\Ticket;
use AbuseIO\Models\Note;
use AbuseIO\Models\Brand;
use AbuseIO\Models\Account;
use Request;
use Input;

/**
 * Controller handling the ASH interface to contacts
 *
 * Class AshController
 * @package AbuseIO\Http\Controllers
 */
class AshController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param integer $ticketID
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function index($ticketID, $token)
    {
        $ticket = Ticket::find($ticketID);
        $AshAuthorisedBy = Request::get('AshAuthorisedBy');

        if ($AshAuthorisedBy == 'TokenIP') {
            $account = Account::find($ticket->ip_contact_account_ip);
        }
        if ($AshAuthorisedBy == 'TokenDomain') {
            $account = Account::find($ticket->domain_contact_account_id);
        }

        $brand = empty($account) ? Brand::getDefault() : $account->active_brand;

        if (empty($brand)) {
            abort(500);
        }

        return view('ash')
            ->with('brand', $brand)
            ->with('ticket', $ticket)
            ->with('allowedChanges', $this->allowedStatusChanges($ticket))
            ->with('token', $token)
            ->with('message', '');

    }

    /**
     * Method to add a note to a ticket
     *
     * @param integer $ticketID
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function addNote($ticketID, $token)
    {
        $submittor = false;

        $ticket = Ticket::find($ticketID);
        $AshAuthorisedBy = Request::get('AshAuthorisedBy');

        if ($AshAuthorisedBy == 'TokenIP') {
            $account = Account::find($ticket->ip_contact_account_ip);
            $submittor = trans('ash.basic.ip') . ' ' . trans('ash.communication.contact');
        }
        if ($AshAuthorisedBy == 'TokenDomain') {
            $account = Account::find($ticket->domain_contact_account_id);
            $submittor = trans('ash.basic.domain') . ' ' . trans('ash.communication.contact');
        }

        $brand = empty($account) ? Brand::getDefault() : $account->brand;

        if (empty($brand) || empty($submittor)) {
            abort(500);
        }

        $changeStatus = Input::get('changeStatus');

        if ($changeStatus == 'IGNORED' || $changeStatus == 'RESOLVED') {
            $ticket->cust_status_id = $changeStatus;
            $ticket->save();
        }

        $text = Input::get('text');
        if (empty($text) || strlen($text) < 1) {
            $message = 'noteEmpty';
        } else {
            $message = 'noteAdded';

            $note = new Note();
            $note->ticket_id = $ticket->id;
            $note->submitter = $submittor;
            $note->text = $text;
            $note->save();
        }

        return view('ash')
            ->with('brand', $brand)
            ->with('ticket', $ticket)
            ->with('allowedChanges', $this->allowedStatusChanges($ticket))
            ->with('token', $token)
            ->with('message', $message);
    }

    /**
     * @param $ticket
     * @return array $allowChanges
     */
    private function allowedStatusChanges($ticket)
    {
        $allowedChanges = [
            'OPEN' => trans('ash.communication.open'),
            'RESOLVED' => trans('ash.communication.resolved'),
        ];

        if ($ticket->type_id == 'INFO') {
            $allowedChanges['IGNORED'] = trans('ash.communication.ignored');
        }

        return $allowedChanges;
    }
}
