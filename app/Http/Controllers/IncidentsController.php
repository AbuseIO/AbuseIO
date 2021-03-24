<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\IncidentFormRequest;
use AbuseIO\Jobs\EvidenceSave;
use AbuseIO\Jobs\IncidentsProcess;
use AbuseIO\Models\Account;
use AbuseIO\Models\Event;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\Incident;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\IncidentTransformer;
use Form;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Log;
use Redirect;

/**
 * Class IncidentsController.
 */
class IncidentsController extends Controller
{
    use Api;

    /**
     * IncidentsController constructor.
     *
     * @param Manager $fractal
     * @param Request $request
     */
    public function __construct(Manager $fractal, Request $request)
    {
        parent::__construct();

        // initialize the Api methods
        $this->apiInit($fractal, $request);

        // is the logged in account allowed to execute an action on the Incident
        $this->middleware('checkaccount:Incident', ['except' => ['search', 'index', 'create', 'store', 'export', 'apiIndex', 'apiShow']]);
    }

    /**
     * Show the form for creating a incident.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('incidents.create')
            ->with('classes', Event::getClassifications())
            ->with('types', Event::getTypes())
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Store a newly created incident in storage.
     *
     * @param IncidentFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(IncidentFormRequest $request)
    {
        // create a new Incident object
        $incident = Incident::create($request->all());

        /*
         * If there was a file attached then we add this to the evidence as attachment
         */
        $attachment = [];
        $uploadedFile = \Illuminate\Support\Facades\Request::file('evidenceFile');
        if (!empty($uploadedFile) &&
            is_object($uploadedFile) &&
            $uploadedFile->getError() === 0 &&
            is_file($uploadedFile->getPathname())
        ) {
            $attachment = [
                'filename'    => $uploadedFile->getClientOriginalName(),
                'size'        => $uploadedFile->getSize(),
                'contentType' => $uploadedFile->getMimeType(),
                'data'        => file_get_contents($uploadedFile->getPathname()),
            ];
        }

        /*
         * Incident process required all incidents to be wrapped in an array.
         */
        $incidents = [
            0 => $incident,
        ];

        /*
         * Save the evidence as its required to save events
         */
        $evidence = new EvidenceSave();
        $evidenceData = [
            'createdBy'     => trim($this->auth_user->fullName()).' ('.$this->auth_user->email.')',
            'receivedOn'    => time(),
            'submittedData' => $incident->toArray(),
            'attachments'   => [],
        ];
        if (!empty($attachment)) {
            $evidenceData['attachments'][0] = $attachment;
        }
        $evidenceFile = $evidence->save(json_encode($evidenceData));

        if (!$evidenceFile) {
            Log::error(
                get_class($this).': '.
                'Error returned while asking to write evidence file, cannot continue'
            );
            $this->exception();
        }

        $evidence = new Evidence();
        $evidence->filename = $evidenceFile;
        $evidence->sender = $this->auth_user->email;
        $evidence->subject = 'AbuseDesk Created Incident';

        /*
         * Call IncidentsProcess to validate, store evidence and save incidents
         */
        $incidentsProcess = new IncidentsProcess($incidents, $evidence);

        // Validate the data set
        $validated = $incidentsProcess->validate();
        if (!$validated) {
            return Redirect::back()->with('message', "Failed to validate incident model {$validated}");
        }

        // Write the data set to database
        if (!$incidentsProcess->save()) {
            return Redirect::back()->with('message', 'Failed to write to database');
        }

        return Redirect::route(
            'admin.tickets.index'
        )->with(
            'message',
            'A new incident has been created. Depending on the aggregator result a new '.
            'ticket will be created or existing ticket updated'
        );
    }

    /**
     * return $this->respondWithItem($ticket, new TicketTransformer());.
     *
     * @param IncidentFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(IncidentFormRequest $request)
    {
        // create a new Incident object
        $incident = Incident::create($request->all());

        /*
         * Incident process required all incidents to be wrapped in an array.
         */
        $incidents = [
            0 => $incident,
        ];

        /*
         * Save the evidence as its required to save events
         */
        $systemAccount = Account::where('systemaccount', '=', true)->first();
        $adminUser = $systemAccount->admins()->first(); // get the first of the admin users
        $evidence = new EvidenceSave();

        // remove remote AbuseIO data
        $submittedData = $incident->toArray();
        foreach ($submittedData as $key => $data) {
            if (preg_match('/^remote_/', $key) == 1) {
                unset($submittedData[$key]);
            }
        }

        $evidenceData = [
            'createdBy'     => trim($adminUser->fullName()).' ('.$adminUser->email.')',
            'receivedOn'    => time(),
            'submittedData' => $submittedData,
            'attachments'   => [],
        ];

        $evidenceFile = $evidence->save(json_encode($evidenceData));

        if (!$evidenceFile) {
            Log::error(
                get_class($this).': '.
                'Error returned while asking to write evidence file, cannot continue'
            );
            $this->exception();
        }

        $evidence = new Evidence();
        $evidence->filename = $evidenceFile;
        $evidence->sender = $adminUser->email;
        $evidence->subject = 'Delegated AbuseIO Incident';

        /*
         * Call IncidentsProcess to validate, store evidence and save incidents
         */
        $incidentsProcess = new IncidentsProcess($incidents, $evidence);

        // Validate the data set
        $validated = $incidentsProcess->validate();
        if (!$validated) {
            return $this->errorWrongArgs("Failed to validate incident model {$validated}");
        }

        // Write the data set to database
        if (!$incidentsProcess->save()) {
            return $this->errorInternalError('Failed to write to database');
        }

        // return the processed Incident
        // TODO: maybe return the Event/Ticket instead
        return $this->respondWithItem($incident, new IncidentTransformer());
    }
}
