<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\TicketsFormRequest;
use AbuseIO\Jobs\Notification;
use AbuseIO\Jobs\TicketUpdate;
use AbuseIO\Models\Evidence;
use AbuseIO\Models\Ticket;
use Illuminate\Filesystem\Filesystem;
use PhpMimeMailParser\Parser as MimeParser;
use yajra\Datatables\Datatables;
use Redirect;
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
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        //$tickets = Ticket::all();
        $tickets = Ticket::select(
            "tickets.*",
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
                    return trans('types.status.' . $ticket->status_id . '.name');
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
        return view('tickets.index')
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Show the form for creating a ticket
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: #AIO-39 Interaction tickets - (bart) implement new ticket by adding events(data)?

        return view('tickets.create')
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
        // TODO #AIO-?? ExportProvider - (mark) Move this into an ExportProvider or something s?
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
                    $ticket->class_id,
                    $ticket->type_id,
                    $ticket->firstEvent[0]->seen,
                    $ticket->lastEvent[0]->seen,
                    $ticket->events->count(),
                    trans('types.status.'.$ticket->status_id.'.name'),
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
     * @param TicketsFormRequest $ticket
     * @return \Illuminate\Http\Response
     */
    public function store(TicketsFormRequest $ticket)
    {
        // TODO: #AIO-39 Interaction tickets - (bart) implement new ticket by adding events(data)?
    }

    /**
     * Display the specified ticket.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $class = trans("types.status.{$ticket->status_id}.class");

        return view('tickets.show')
            ->with('ticket', $ticket)
            ->with('ticket_class', $class)
            ->with('auth_user', $this->auth_user);
    }

    /**
     * Update the requested contact information.
     *
     * @param  Ticket $ticket    Ticket Model
     * @param  string $only      Only send to ('ip', 'domain' or null (both))
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
     * @param  Ticket $ticket    Ticket Model
     * @param  string $newstatus String version of the new status
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
     * @param Ticket $ticket    Ticket Model
     * @param string $only      Only send to ('ip', 'domain' or null (both))
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

    /**
     * Shows the evidence for this ticket as webpage.
     *
     * @param Ticket $ticket        Ticket Model
     * @param integer $evidenceId
     * @return \Illuminate\Http\Response
     */
    public function viewEvidence(Ticket $ticket, $evidenceId)
    {
        $evidence = Evidence::find($evidenceId);

        if (!$evidence) {
            return Redirect::route('admin.tickets.show', $ticket->id)
                ->with('message', 'The evidence is no longer available for this event.');
        }

        if (is_file($evidence->filename)) {
            $evidenceData = file_get_contents($evidence->filename);

            $evidenceParsed = new MimeParser();
            $evidenceParsed->setText($evidenceData);

            $filesystem = new Filesystem;
            $evidenceTempDir = "/tmp/abuseio/cache/{$ticket->id}/{$evidenceId}/";
            if (!$filesystem->isDirectory($evidenceTempDir)) {
                if (!$filesystem->makeDirectory($evidenceTempDir, 0755, true)) {
                    Log::error(
                        get_class($this) . ': ' .
                        'Unable to create temp directory: ' . $evidenceTempDir
                    );
                }
                $evidenceParsed->saveAttachments($evidenceTempDir);
            }

            return view('tickets.evidence')
                ->with('ticket', $ticket)
                ->with('evidence', $evidenceParsed)
                ->with('evidenceId', $evidenceId)
                ->with('evidenceTempDir', $evidenceTempDir)
                ->with('auth_user', $this->auth_user);

        } else {
            return Redirect::route('admin.tickets.show', $ticket->id)
                ->with('message', 'ERROR: The file was not available on the filesystem.');
        }

    }

    /**
     * Downloads an evidence attachment for this tickets evidence
     *
     * @param Ticket $ticket        Ticket Model
     * @param integer $evidenceId
     * @param string $attachmentFile
     * @return \Illuminate\Http\Response
     */
    public function downloadEvidenceAttachment(Ticket $ticket, $evidenceId, $attachmentFile)
    {
        $evidenceTempDir = "/tmp/abuseio/cache/{$ticket->id}/{$evidenceId}/";
        $downloadFile = $evidenceTempDir . $attachmentFile;

        if (is_file($downloadFile)) {
            $evidenceData = file_get_contents($downloadFile);

            return response($evidenceData, 200)
                ->header('Content-Type', 'message/rfc822')
                ->header('Content-Transfer-Encoding', 'Binary')
                ->header('Content-Disposition', 'attachment; filename="' . $attachmentFile . '"');
        } else {
            return Redirect::route('admin.tickets.show', $ticket->id)
                ->with('message', 'ERROR: The file was not available on the filesystem.');
        }
    }

    /**
     * Downloads the evidence for this ticket as EML
     *
     * @param Ticket $ticket        Ticket Model
     * @param integer $evidenceId
     * @return \Illuminate\Http\Response
     */
    public function downloadEvidence(Ticket $ticket, $evidenceId)
    {
        $evidence = Evidence::find($evidenceId);

        if (!$evidence) {
            return Redirect::route('admin.tickets.show', $ticket->id)
                ->with('message', 'The evidence is no longer available for this event.');
        }

        if (is_file($evidence->filename)) {
            $evidenceData = file_get_contents($evidence->filename);
            $outputFilename = "ticket_{$ticket->id}_evidence_{$evidenceId}.eml";

            return response($evidenceData, 200)
                ->header('Content-Type', 'message/rfc822')
                ->header('Content-Transfer-Encoding', 'Binary')
                ->header('Content-Disposition', 'attachment; filename="' . $outputFilename . '"');
        } else {
            return Redirect::route('admin.tickets.show', $ticket->id)
                ->with('message', 'ERROR: The file was not available on the filesystem.');
        }
    }
}
