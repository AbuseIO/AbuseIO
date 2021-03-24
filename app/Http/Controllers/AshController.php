<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Models\Account;
use AbuseIO\Models\Brand;
use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use App;
use Request;
use Session;

/**
 * Controller handling the ASH interface to contacts.
 *
 * Class AshController
 */
class AshController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int    $ticketID
     * @param string $token
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ticketID, $token)
    {
        $ticket = Ticket::find($ticketID);
        $AshAuthorisedBy = Request::get('AshAuthorisedBy');

        if ($AshAuthorisedBy == 'TokenIP') {
            $account = Account::find($ticket->ip_contact_account_id);
        }
        if ($AshAuthorisedBy == 'TokenDomain') {
            $account = Account::find($ticket->domain_contact_account_id);
        }

        $brand = empty($account) ? Brand::getSystemBrand() : $account->brand;

        if (empty($brand)) {
            abort(500);
        }

        $replacements = [
            'brand'          => $brand,
            'ticket'         => $ticket,
            'allowedChanges' => $this->allowedStatusChanges($ticket),
            'token'          => $token,
            'message'        => '',
            'language'       => App::getLocale(),
        ];

        if (Session::has('message')) {
            $replacements['message'] = Session::get('message');
        }

        $view = view('ash', $replacements);

        if ($brand->ash_custom_template) {
            // defensive programming, doubble check the templates
            $validator = \Validator::make(
                [
                    'ash' => $brand->ash_template,
                ],
                [
                    'ash' => 'required|bladetemplate',
                ]
            );

            if ($validator->passes()) {
                try {
                    // only use the template if they pass the validation
                    $custom_view = view(['template' => $brand->ash_template], $replacements);

                    // try to render the view (missing vars will throw an exception)
                    $custom_view->render();

                    // no errors occurred while rendering
                    $view = $custom_view;
                } catch (\ErrorException $e) {
                    \Log::warning('Incorrect ash template, falling back to default: '.$e->getMessage());
                }
            } else {
                \Log::warning("Template isn't a valid blade template, falling back to default");
            }
        }

        return $view;
    }

    /**
     * Method to add a note to a ticket.
     *
     * @param int    $ticketID
     * @param string $token
     *
     * @return \Illuminate\Http\Response
     */
    public function addNote($ticketID, $token)
    {
        $submittor = false;

        $ticket = Ticket::find($ticketID);
        $AshAuthorisedBy = Request::get('AshAuthorisedBy');

        if ($AshAuthorisedBy == 'TokenIP') {
            $submittor = trans('ash.basic.ip').' '.trans('ash.communication.contact');
        }
        if ($AshAuthorisedBy == 'TokenDomain') {
            $submittor = trans('ash.basic.domain').' '.trans('ash.communication.contact');
        }

        if (empty($submittor)) {
            abort(500);
        }

        $changeStatus = \Illuminate\Support\Facades\Request::get('changeStatus');

        if ($changeStatus == 'IGNORED' || $changeStatus == 'RESOLVED') {
            $ticket->contact_status_id = $changeStatus;
            $ticket->save();
        }

        $text = \Illuminate\Support\Facades\Request::get('text');
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

        return redirect(route('ash.show', [$ticket->id, $token]))->with(compact('message'));
    }

    /**
     * @param $ticket
     *
     * @return array $allowChanges
     */
    private function allowedStatusChanges($ticket)
    {
        $allowedChanges = [
            'OPEN'     => trans('ash.communication.open'),
            'RESOLVED' => trans('ash.communication.resolved'),
        ];

        if ($ticket->type_id == 'INFO') {
            $allowedChanges['IGNORED'] = trans('ash.communication.ignored');
        }

        return $allowedChanges;
    }
}
