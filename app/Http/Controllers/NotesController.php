<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\NoteFormRequest;
use AbuseIO\Http\Requests\StoreNoteRequest;
use AbuseIO\Http\Requests\UpdateNoteRequest;
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
        $this->middleware(\AbuseIO\Http\Middleware\CheckAccount::class.':Note', ['except' => ['index', 'create', 'store', 'apiIndex', 'apiStore', 'apiShow', 'apiUpdate', 'apiDestroy']]);
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
     * @param StoreNoteRequest $noteForm
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreNoteRequest $noteForm)
    {
        Note::create($noteForm->all());
        $this->sendNotification($noteForm);

        $redirectUrl = route('admin.tickets.show', $noteForm->ticket_id);
        // Preserve Communication tab active state
        $redirectUrl .= '#communication';

        return Redirect::to($redirectUrl)
            ->with('message', 'A new note for this ticket has been created');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNoteRequest $noteForm
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apiStore(StoreNoteRequest $noteForm)
    {
        global $testrunner;

        $note = Note::create($noteForm->all());

        if (!$testrunner) {
            $this->sendNotification($noteForm);
        }

        return  $this->respondWithItem($note, new NoteTransformer());
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $notes = Note::all();

        return $this->respondWithCollection($notes, new NoteTransformer());
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
     * @param UpdateNoteRequest $noteForm
     * @param Note            $notes
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNoteRequest $noteForm, Note $notes)
    {
        $input = $noteForm->all();

        switch ($input['action']) {
            case 'hide':
                $notes->hidden = !$notes->hidden;
                break;
            case 'view':
                $notes->viewed = !$notes->viewed;
                break;
            default:
                // code...
                break;
        }
        $notes->save();

        return 'flip:OK';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Note            $notes
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $notes)
    {
        $notes->delete();

        return 'delete:OK';
    }

    /**
     * Send notifiction if NoteForm not is hidden;.
     *
     * @param StoreNoteRequest $noteForm
     */
    protected function sendNotification(StoreNoteRequest $noteForm)
    {
        if ($noteForm->hidden != true) {
            $notification = new Notification();
            $notifications = $notification->buildList($noteForm->ticket_id, false, true);
            $notification->walkList($notifications);
        }
    }
}
