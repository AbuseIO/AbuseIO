<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\NoteFormRequest;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Note;
use Redirect;
use Input;
use Form;

class NotesController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // No requirement for implementation
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // No requirement for implementation
    }

    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store(NoteFormRequest $note)
    {
        if (config('main.notes.show_abusedesk_names') === true) {
            $postingUser = ' (' . $this->auth_user->fullName() . ')';
        } else {
            $postingUser = '';
        }

        $input = Input::all();

        $input['submitter'] = trans('ash.communication.abusedesk'). $postingUser;
        $input['viewed'] = true;
        Note::create($input);

        return Redirect::route(
            'admin.tickets.show',
            $note->ticket_id
        )->with('message', 'A new note for this ticket has been created');
    }

    /**
     * Display the specified resource.
     * @return Response
     */
    public function show()
    {
        // No requirement for implementation
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Note $note)
    {
        // No requirement for implementation
    }

    /**
     * Update the specified resource in storage.
     * @param Domain $domain
     * @return Response
     * @internal param int $id
     */
    public function update(NoteFormRequest $request, Note $note)
    {
        $input = Input::all();

        if (isset($input['hidden'])) {
            $note->hidden = $input['hidden'];
            if ($input['hidden'] == true) {
                $message = 'hidden';
            }
            if ($input['hidden'] != true) {
                $message = 'unhidden';
            }
        }
        if (isset($input['viewed'])) {
            $note->viewed = $input['viewed'];
            if ($input['viewed'] == true) {
                $message = 'read';
            }
            if ($input['viewed'] != true) {
                $message = 'unread';
            }
        }

        $note->save();

        return Redirect::route(
            'admin.tickets.show',
            $note->ticket_id
        )->with('message', 'The note has been marked ' . $message);
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return Response
     */
    public function destroy(Note $note)
    {
        $returnTicket = $note->ticket_id;

        $note->delete();

        return Redirect::route(
            'admin.tickets.show',
            $returnTicket
        )->with('message', 'The note has been deleted');
    }
}
