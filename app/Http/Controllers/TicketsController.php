<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\TicketFormRequest;
use AbuseIO\Jobs\Notification;
use AbuseIO\Jobs\TicketUpdate;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\Incident;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Event;
use AbuseIO\Jobs\EvidenceSave;
use AbuseIO\Jobs\IncidentsProcess;
use Illuminate\Filesystem\Filesystem;
use PhpMimeMailParser\Parser as MimeParser;
use yajra\Datatables\Datatables;
use Redirect;
use Input;
use Lang;
use Log;
use DB;

/**
 * Class TicketsController
 * @package AbuseIO\Http\Controllers
 */
class TicketsController extends Controller
{

    /**
     * TicketsController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // is the logged in account allowed to execute an action on the Domain
        $this->middleware('checkaccount:Ticket', ['except' => ['search', 'index', 'create', 'store', 'export']]);

    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {

        $auth_account = $this->auth_user->account;

        $tickets = Ticket::select(
            'tickets.id',
            'tickets.ip',
            'tickets.domain',
            'tickets.type_id',
            'tickets.class_id',
            'tickets.status_id',
            'tickets.ip_contact_account_id',
            'tickets.ip_contact_reference',
            'tickets.ip_contact_name',
            'tickets.domain_contact_account_id',
            'tickets.domain_contact_reference',
            'tickets.domain_contact_name',
            DB::raw("count(distinct events.id) as event_count"),
            DB::raw("count(distinct notes.id) as notes_count")
        )
            ->leftJoin('events', 'events.ticket_id', '=', 'tickets.id')
            ->leftJoin(
                'notes',
                function ($join) {
                // We need a LEFT JOIN .. ON .. AND ..).
                // This doesn't exist within Illuminate's JoinClause class
                // So we use some nesting foo here
                    $join->on('notes.ticket_id', '=', 'tickets.id')
                        ->nest(
                            function ($join) {
                                $join->on('notes.viewed', '=', DB::raw("'false'"));
                            }
                        );
                }
            )
            ->groupBy('tickets.id');

        if (!$auth_account->isSystemAccount()) {
            // We're using a grouped where clause here, otherwise the filtering option
            // will always show the same result (all tickets)
            $tickets = $tickets->where(function ($query) use ($auth_account) {
                $query->where('tickets.ip_contact_account_id', '=', $auth_account->id)
                    ->orWhere('tickets.domain_contact_account_id', '=', $auth_account->id);
            });
        }

        return Datatables::of($tickets)
            // Create the action buttons
            ->addColumn(
                'actions',
                function ($ticket) {
                    $actions = ' <a href="tickets/' . $ticket->id .
                        '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-eye-open"></span> '.
                        trans('misc.button.show').'</a> ';

                    return $actions;
                }
            )
            ->editColumn(
                'type_id',
                function ($ticket) {
                    return trans('types.type.' . $ticket->type_id . '.name');
                }
            )
            ->editColumn(
                'class_id',
                function ($ticket) {
                    return trans('classifications.' . $ticket->class_id . '.name');
                }
            )
            ->editColumn(
                'status_id',
                function ($ticket) {
                    return trans('types.status.abusedesk.' . $ticket->status_id . '.name');
                }
            )
            ->make(true);
    }

    /**
     * Display all tickets
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get translations for all statuses
        $statuses = Event::getStatuses();

        return view('tickets.index')
            ->with('types', Event::getTypes())
            ->with('classes', Event::getClassifications())
            ->with('statuses', $statuses['abusedesk'])
            ->with('contact_statuses', $statuses['contact'])
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a ticket
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create')
            ->with('classes', Event::getClassifications())
            ->with('types', Event::getTypes())
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Export tickets to CSV format.
     *
     * @param string $format
     * @return \Illuminate\Http\Response
     */
    public function export($format)
    {
        // TODO #AIO-?? ExportProvider - (mark) Move this into an ExportProvider or something?
        $tickets = Ticket::all();

        if ($format === 'csv') {
            $columns = [
                'id'            => 'Ticket ID',
                'ip'            => 'IP address',
                'class_id'      => 'Classification',
                'type_id'       => 'Type',
                'first_seen'    => 'First seen',
                'last_seen'     => 'Last seen',
                'event_count'   => 'Events',
                'status_id'     => 'Ticket Status',
            ];

            $output = '"' . implode('", "', $columns) . '"' . PHP_EOL;

            foreach ($tickets as $ticket) {
                $row = [
                    $ticket->id,
                    $ticket->ip,
                    trans("classifications.{$ticket->class_id}.name"),
                    trans("types.type.{$ticket->type_id}.name"),
                    $ticket->firstEvent[0]->seen,
                    $ticket->lastEvent[0]->seen,
                    $ticket->events->count(),
                    trans("types.status.abusedesk.{$ticket->status_id}.name"),
                ];

                $output .= '"' . implode('", "', $row) . '"' . PHP_EOL;
            }

            return response(substr($output, 0, -1), 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="Tickets.csv"');
        }

        return Redirect::route('admin.contacts.index')
            ->with('message', "The requested format {$format} is not available for exports");
    }

    /**
     * Store a newly created ticket in storage.
     *
     * @param TicketFormRequest $ticket
     * @return \Illuminate\Http\Response
     */
    public function store(TicketFormRequest $ticket)
    {
        /*
         * If there was a file attached then we add this to the evidence as attachment
         */
        $attachment = [];
        $uploadedFile = Input::file('evidenceFile');
        if (!empty($uploadedFile) &&
            is_object($uploadedFile) &&
            $uploadedFile->getError() === 0 &&
            is_file($uploadedFile->getPathname())
        ) {
            $attachment = [
                'filename' => $uploadedFile->getClientOriginalName(),
                'size' => $uploadedFile->getSize(),
                'contentType' => $uploadedFile->getMimeType(),
                'data' => file_get_contents($uploadedFile->getPathname())
            ];
        }

        /*
         * Grab the form and build a incident model from it. The form should be having all the fields except
         * the form token. We don't need to validate the data as the formRequest already to care of this and
         * IncidentsSave will do another validation on this.
         */
        $incident = new Incident;
        foreach ($ticket->all() as $key => $value) {
            if ($key != '_token') {
                $incident->$key = $value;
            }
        }

        /*
         * Incident process required all incidents to be wrapped in an array.
         */
        $incidents = [
            0 => $incident
        ];

        /*
         * Save the evidence as its required to save events
         */
        $evidence = new EvidenceSave;
        $evidenceData = [
            'CreatedBy'     => trim($this->auth_user->fullName()) . ' (' . $this->auth_user->email .')',
            'receivedOn'    => time(),
            'submittedData' => $ticket->all(),
            'attachments'   => [],
        ];
        if (!empty($attachment)) {
            $evidenceData['attachments'][0] = $attachment;
        }
        $evidenceFile = $evidence->save(json_encode($evidenceData));

        if (!$evidenceFile) {
            Log::error(
                get_class($this) . ': ' .
                'Error returned while asking to write evidence file, cannot continue'
            );
            $this->exception();
        }


        $evidence = new Evidence();
        $evidence->filename = $evidenceFile;
        $evidence->sender = $this->auth_user->email;
        $evidence->subject = 'AbuseDesk Created Incident';

        /**
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
            'A new incident has been created. Depending on the aggregator result a new ' .
            'ticket will be created or existing ticket updated'
        );
    }

    /**
     * Display the specified ticket.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show')
            ->with('ticket', $ticket)
            ->with('ticket_class', config("types.status.abusedesk.{$ticket->status_id}.class"))
            ->with('contact_ticket_class', config("types.status.contact.{$ticket->contact_status_id}.class"))
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the requested contact information.
     *
     * @param  Ticket $ticket
     * @param  string $only
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Ticket $ticket, $only = null)
    {
        TicketUpdate::contact($ticket, $only);

        return Redirect::route('admin.tickets.show', $ticket->id)
            ->with('message', 'Contact has been updated.');
    }

    /**
     * Set the status of a tickets
     *
     * @param  Ticket $ticket
     * @param  string $newstatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status(Ticket $ticket, $newstatus)
    {
        TicketUpdate::status($ticket, $newstatus);
        return Redirect::route('admin.tickets.show', $ticket->id)
            ->with('message', 'Ticket status has been updated.');
    }

    /**
     * Send a notification for this ticket to the IP contact.
     *
     * @param Ticket $ticket
     * @param string $only
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notify(Ticket $ticket, $only = null)
    {
        $notification = new Notification;
        $notification->walkList(
            $notification->buildList($ticket->id, false, true, $only)
        );

        return Redirect::route('admin.tickets.show', $ticket->id)
            ->with('message', 'Contact has been notified.');
    }
}
