<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\NoteFormRequest;
use AbuseIO\Jobs\Notification;
use AbuseIO\Models\Note;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\NoteTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Redirect;

/**
 * Class NotesController.
 */
class NotesController extends Controller
{
    use Api;

    /**
     * NotesController constructor.
     *
     * @param Manager $fractal
     * @param Request $request
     */
    public function __construct(Manager $fractal, Request $request)
    {
        parent::__construct();

        $this->apiInit($fractal, $request);

        // is the logged in account allowed to execute an action on the Domain
        //$this->middleware('checkaccount:Notes', ['except' => ['search', 'index', 'create', 'store', 'export']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // No requirement for implementation
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // No requirement for implementation
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NoteFormRequest $noteForm
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NoteFormRequest $noteForm)
    {
        Note::create($noteForm->all());

        /*
         * send notication if a new note is added
         */
        if ($noteForm->hidden != true) {
            $notification = new Notification();
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
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // No requirement for implementation
    }

    /**
     * @param Note $note
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiShow(Note $note)
    {
        return $this->respondWithItem($note, new NoteTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Note $note
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        // No requirement for implementation
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoteFormRequest $noteForm
     *
     * @return \Illuminate\Http\Response
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
                // code...
                break;
        }
        $note->save();

        return 'flip:OK';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param NoteFormRequest $noteForm
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(NoteFormRequest $noteForm)
    {
        $input = $noteForm->all();
        $note = Note::find($input['note']);
        $note->delete();

        return 'delete:OK';
    }
}
