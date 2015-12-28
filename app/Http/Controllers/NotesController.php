<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\NoteFormRequest;
use AbuseIO\Jobs\Notification;
use AbuseIO\Models\Note;
use Redirect;

class NotesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // No requirement for implementation
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // No requirement for implementation
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(NoteFormRequest $noteForm)
    {
        Note::create($noteForm->all());

        /*
         * send notication if a new note is added
         */
        if ($noteForm->hidden != true){
            $notification = new Notification;
            $notifications = $notification->buildList($noteForm->ticket_id, false, true);
            $notification->walkList($notifications);
        }

        return Redirect::route(
            'admin.tickets.show',
            $noteForm->ticket_id
        )->with('message', 'A new note for this ticket has been created');
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        // No requirement for implementation
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Note $note)
    {
        // No requirement for implementation
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Domain $domain
     * @return Response
     * @internal param int $id
     */
    public function update(NoteFormRequest $noteForm)
    {
        $input = $noteForm->all();
        $note = Note::find($input['note']);

        switch ($input['action']) {
            case 'hide':
                $note->hidden = !$note->hidden;
                break;
            case 'view':
                $note->viewed = !$note->viewed;
                break;
            default:
                # code...
                break;
        }
        $note->save();

        return 'flip:OK';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(NoteFormRequest $noteForm)
    {
        $input = $noteForm->all();
        $note = Note::find($input['note']);
        $note->delete();

        return 'delete:OK';
    }
}
