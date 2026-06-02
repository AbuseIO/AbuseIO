<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests\TicketFormRequest;
use AbuseIO\Jobs\Notification;
use AbuseIO\Jobs\TicketUpdate;
use AbuseIO\Models\Event;
use AbuseIO\Models\Note;
use AbuseIO\Models\Ticket;
use AbuseIO\Traits\Api;
use AbuseIO\Transformers\TicketTransformer;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Redirect;
use yajra\Datatables\Datatables;
use Zend\Json\Json;

/**
 * Class TicketsController.
 */
class TicketsController extends Controller
{
    use Api;

    /**
     * TicketsController constructor.
     */
    public function __construct(Manager $fractal, Request $request)
    {
        parent::__construct();
        $this->apiInit($fractal, $request);

        $this->middleware(\AbuseIO\Http\Middleware\CheckSystemAccount::class, ['only' => ['create', 'store']]);
        $this->middleware(\AbuseIO\Http\Middleware\CheckAccount::class.':Ticket', ['except' => ['index', 'create', 'store', 'apiIndex', 'apiStore', 'apiShow', 'apiUpdate', 'apiDestroy']]);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $auth_account = $this->auth_user->account;
        $auth_user = $this->auth_user;

        // retrieve the filters and column order
        $columns = $request->input('columns');
        $order = $request->input('order');

        if (is_array($columns)) {
            foreach (
                [
                    'ticket_type_filter'           => 5,
                    'ticket_classification_filter' => 6,
                    'ticket_status_filter'         => 9,
                ] as $filter => $column
            ) {
                // save the type/class/status filter option in the user
                if (array_key_exists($column, $columns) &&
                    array_key_exists('search', $columns[$column]) &&
                    array_key_exists('value', $columns[$column]['search'])) {
                    $auth_user->setOption($filter, $columns[$column]['search']['value']);
                }
            }
        }

        // check to see if an order is given and save it in the user
        if (is_array($order)) {
            if (array_key_exists(0, $order)) {
                $auth_user->setOption('ticket_sort_order', $order[0]);
            }
        }

        $tickets = Ticket::select(
            'tickets.id',
            'tickets.ip',
            'tickets.domain',
            'tickets.type_id',
            'tickets.class_id',
            'tickets.status_id',
            'tickets.updated_at',
            'tickets.ip_contact_account_id',
            'tickets.ip_contact_reference',
            'tickets.ip_contact_name',
            'tickets.domain_contact_account_id',
            'tickets.domain_contact_reference',
            'tickets.domain_contact_name',
            DB::raw('count(distinct events.id) as event_count'),
            DB::raw('count(distinct notes_all.id) as notes_count'),
            DB::raw('count(distinct notes_unread.id) as unread_notes_count')
        )
            ->leftJoin('events', 'events.ticket_id', '=', 'tickets.id')
            // Join all notes (for total count)
            ->leftJoin('notes as notes_all', function ($join) {
                $join->on('notes_all.ticket_id', '=', 'tickets.id');
            })
            // Join unread notes (for unread count)
            ->leftJoin('notes as notes_unread', function ($join) {
                $join->on('notes_unread.ticket_id', '=', 'tickets.id')
                    ->on('notes_unread.viewed', '=', DB::raw("'false'"));
            })
            // Exclude soft-deleted notes from both joins
            ->whereNull('notes_all.deleted_at')
            ->whereNull('notes_unread.deleted_at')
            ->groupBy('tickets.id');

        if (!$auth_account->isSystemAccount()) {
            // We're using a grouped where clause here, otherwise the filtering option
            // will always show the same result (all tickets)
            $tickets = $tickets->where(
                function ($query) use ($auth_account) {
                    $query->where('tickets.ip_contact_account_id', '=', $auth_account->id)
                        ->orWhere('tickets.domain_contact_account_id', '=', $auth_account->id);
                }
            );
        }

        return Datatables::of($tickets)
            // Create the action buttons
            ->addColumn(
                'actions',
                function ($ticket) {
                    $actions = ' <a href="tickets/'.$ticket->id.
                        '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> '.
                        trans('misc.button.show').'</a> ';

                    return $actions;
                }
            )
            // Custom filter for status column to support combined option OPEN+ESCALATED
            // Target the column name used by DataTables ('tickets.status_id') so the override applies
            ->filterColumn('tickets.status_id', function ($query, $keyword) {
                $keyword = strtoupper(trim((string) $keyword));
                if ($keyword === 'OPEN_ESCALATED') {
                    $query->whereIn('tickets.status_id', ['OPEN', 'ESCALATED']);
                } elseif ($keyword !== '') {
                    // Use exact match for explicit statuses
                    $query->where('tickets.status_id', $keyword);
                }
            })
            // Filter for notes column: allow selecting only tickets with unread notes
            ->filterColumn('notes_count', function ($query, $keyword) {
                $keyword = strtoupper(trim((string) $keyword));
                if ($keyword === 'UNREAD') {
                    // Robust filter: use EXISTS to ensure tickets with at least one unread note
                    $query->whereExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('notes as n')
                            ->whereColumn('n.ticket_id', 'tickets.id')
                            ->where('n.viewed', '=', 'false')
                            ->whereNull('n.deleted_at');
                    });
                }
            })
            // Ensure unread filtering applies even if column order differs
            ->filter(function ($query) use ($request) {
                $columns = $request->input('columns');
                if (!is_array($columns)) {
                    return;
                }
                foreach ($columns as $col) {
                    $dataName = $col['data'] ?? '';
                    $searchVal = $col['search']['value'] ?? '';
                    if ($dataName === 'notes_count' && strtoupper(trim((string) $searchVal)) === 'UNREAD') {
                        $query->whereExists(function ($sub) {
                            $sub->select(DB::raw(1))
                                ->from('notes as n')
                                ->whereColumn('n.ticket_id', 'tickets.id')
                                ->where('n.viewed', '=', 'false')
                                ->whereNull('n.deleted_at');
                        });
                        break;
                    }
                }
            }, true)
            ->editColumn(
                'type_id',
                function ($ticket) {
                    return trans('types.type.'.$ticket->type_id.'.name');
                }
            )
            ->editColumn(
                'class_id',
                function ($ticket) {
                    $canonical = classificationLookup($ticket->class_id) ?? $ticket->class_id;

                    return trans('classifications.'.$canonical.'.name');
                }
            )
            ->editColumn(
                'status_id',
                function ($ticket) {
                    return trans('types.status.abusedesk.'.$ticket->status_id.'.name');
                }
            )
            // Format Last Update as d-m-Y H:i and prevent wrapping
            ->editColumn(
                'updated_at',
                function ($ticket) {
                    $formatted = date('d-m-Y H:i', strtotime($ticket->getOriginal('updated_at')));

                    return '<span style="white-space: nowrap;">'.$formatted.'</span>';
                }
            )
            // Render notes total with unread badge/icon when present
            ->editColumn(
                'notes_count',
                function ($ticket) {
                    $total = (int) ($ticket->notes_count ?? 0);
                    $unread = (int) ($ticket->unread_notes_count ?? 0);
                    $html = (string) $total;
                    if ($unread > 0) {
                        $html .= ' <span class="badge bg-warning text-dark" title="Unread notes">'
                            .'<i class="fa fa-envelope"></i> '
                            .$unread
                            .'</span>';
                    }

                    return $html;
                }
            )
            ->rawColumns(['actions', 'updated_at', 'notes_count'])
            ->make(true);
    }

    /**
     * api search
     * expects query criteria in the body of the request
     * eg :
     * {
     *   "criteria":
     *   [
     *     {
     *        "column": "ip",
     *        "operator": "like",
     *        "value": "%10%"
     *     },
     *     {
     *        "column": "id",
     *        "operator": ">",
     *        "value": 7
     *     }
     *   ],
     *   "orderby": "ip",
     *   "desc": true,
     *   "limit": "5"
     * }.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSearch(Request $request)
    {
        $api_account = $this->api_account;
        $body = $request->getContent();
        $post_process = [];
        $mapped_columns = [
            'event_count',
            'note_count',
        ];

        try {
            $query = Json::decode($body, Json::TYPE_OBJECT);
        } catch (\Exception $e) {
            return $this->errorInternalError('Faulty JSON request');
        }

        // construct model query
        $tickets = Ticket::query();
        if (isset($query->criteria)) {
            foreach ($query->criteria as $c) {
                // check if we have al the right properties in the criteria
                if (!(isset($c->column) && isset($c->value))) {
                    return $this->errorWrongArgs('Criteria field is missing');
                }

                // no operator, set it to 'equals'
                $c->operator = isset($c->operator) ? $c->operator : '=';

                // skip mapped columns, process them later
                if (in_array($c->column, $mapped_columns)) {
                    array_push($post_process, $c);
                    continue;
                }

                // Canonicalize classification lookups by alias
                if ($c->column === 'class_id' && is_string($c->value)) {
                    $canonical = classificationLookup($c->value);
                    if ($canonical !== null) {
                        $c->value = $canonical;
                    }
                }

                $tickets = $tickets->where($c->column, $c->operator, $c->value);
            }
        }

        // only show the tickets from the authorized account (or all if it is the system account)
        if (!$api_account->isSystemAccount()) {
            $tickets = $tickets->where(
                function ($query) use ($api_account) {
                    $query->where('ip_contact_account_id', '=', $api_account->id)
                        ->orWhere('domain_contact_account_id', '=', $api_account->id);
                }
            );
        }

        // execute the db query
        try {
            $result = $tickets->get();
        } catch (QueryException $e) {
            return $this->errorInternalError($e->getMessage());
        }

        // post process the collection, filter on the the dynamic fields
        foreach ($post_process as $c) {
            $result = $result->filter(function ($object) use ($c) {
                $column = $c->column;
                $value = $object->$column;

                switch ($c->operator) {
                    case '>':
                        $success = $value > $c->value;
                        break;
                    case '<':
                        $success = $value < $c->value;
                        break;
                    case '=':
                        $success = $value == $c->value;
                        break;
                    case '!=':
                        $success = $value != $c->value;
                        break;
                    case '>=':
                        $success = $value >= $c->value;
                        break;
                    case '<=':
                        $success = $value <= $c->value;
                        break;
                    default:
                        $success = true;
                        break;  // not implemented or unknown operator
                }

                return $success;
            });
        }

        // order the results
        $sortmethod = 'sortBy';
        if (isset($query->desc) && $query->desc) {
            $sortmethod = 'sortByDesc';
        }
        if (isset($query->orderby)) {
            $result = $result->$sortmethod(function ($object) use ($query) {
                $column = $query->orderby;

                return $object->$column;
            });
        }

        // offset the results
        if (isset($query->offset)) {
            $result = $result->splice($query->offset);
        }

        // limit the results
        if (isset($query->limit)) {
            $result = $result->take($query->limit);
        }

        return $this->respondWithCollection($result, new TicketTransformer());
    }

    /**
     * Display all tickets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get translations for all statuses
        $statuses = Event::getStatuses();
        $user = $this->auth_user;
        $auth_account = $this->auth_user->account;

        // Determine if there are any unread notes for tickets visible to this account
        $hasUnreadNotes = DB::table('notes')
            ->join('tickets', 'notes.ticket_id', '=', 'tickets.id')
            ->where('notes.viewed', '=', 'false')
            ->whereNull('notes.deleted_at')
            ->whereNull('tickets.deleted_at')
            ->when(!$auth_account->isSystemAccount(), function ($query) use ($auth_account) {
                $query->where(function ($q) use ($auth_account) {
                    $q->where('tickets.ip_contact_account_id', '=', $auth_account->id)
                      ->orWhere('tickets.domain_contact_account_id', '=', $auth_account->id);
                });
            })
            ->exists();

        $json_options = json_encode($user->options ? $user->options : []);

        return view('tickets.index')
            ->with('types', Event::getTypes())
            ->with('classes', Event::getClassifications())
            ->with('statuses', $statuses['abusedesk'])
            ->with('contact_statuses', $statuses['contact'])
            ->with('auth_user', $this->auth_user)
            ->with('user_options', $json_options)
            ->with('has_unread_notes', $hasUnreadNotes);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $api_account = $this->api_account;

        $tickets = Ticket::query();

        // only show the tickets from the authorized account (or all if it is the system account)
        if (!$api_account->isSystemAccount()) {
            $tickets = $tickets->where(
                function ($query) use ($api_account) {
                    $query->where('ip_contact_account_id', '=', $api_account->id)
                        ->orWhere('domain_contact_account_id', '=', $api_account->id);
                }
            );
        }

        $tickets = $tickets->get();

        return $this->respondWithCollection($tickets, new TicketTransformer());
    }

    /**
     * Export tickets to CSV format.
     *
     * @param string $format
     *
     * @return \Illuminate\Http\Response
     */
    public function export($format)
    {
        // TODO #AIO-?? ExportProvider - (mark) Move this into an ExportProvider or something?

        // only export all tickets when we are in the systemaccount
        $auth_account = $this->auth_user->account;
        if ($auth_account->isSystemAccount()) {
            $tickets = Ticket::all();
        } else {
            $tickets = Ticket::select('tickets.*')
                ->where('ip_contact_account_id', $auth_account->id)
                ->orWhere('domain_contact_account_id', $auth_account);
        }

        if ($format === 'csv') {
            $columns = [
                'id'          => 'Ticket ID',
                'ip'          => 'IP address',
                'class_id'    => 'Classification',
                'type_id'     => 'Type',
                'first_seen'  => 'First seen',
                'last_seen'   => 'Last seen',
                'event_count' => 'Events',
                'status_id'   => 'Ticket Status',
            ];

            $output = '"'.implode('", "', $columns).'"'.PHP_EOL;

            foreach ($tickets as $ticket) {
                $canonicalClass = classificationLookup($ticket->class_id) ?? $ticket->class_id;
                $row = [
                    $ticket->id,
                    $ticket->ip,
                    trans("classifications.{$canonicalClass}.name"),
                    trans("types.type.{$ticket->type_id}.name"),
                    $ticket->firstEvent[0]->seen,
                    $ticket->lastEvent[0]->seen,
                    $ticket->events->count(),
                    trans("types.status.abusedesk.{$ticket->status_id}.name"),
                ];

                $output .= '"'.implode('", "', $row).'"'.PHP_EOL;
            }

            return response(substr($output, 0, -1), 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="Tickets.csv"');
        }

        return Redirect::route('admin.contacts.index')
            ->with('message', "The requested format {$format} is not available for exports");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TicketFormRequest $ticketForm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStore(TicketFormRequest $ticketForm)
    {
        $ticket = Ticket::create($ticketForm->all());

        if (!$this->isAllowedForApiAccount($ticket)) {
            return $this->errorNotFound();
        }

        return $this->respondWithItem($ticket, new TicketTransformer());
    }

    /**
     * Display the specified ticket.
     *
     * @param Ticket $ticket
     *
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
     * Display the specified resource.
     *
     * @param Ticket $ticket
     *
     * @return \Illuminate\Http\Response
     */
    public function apiShow(Ticket $ticket)
    {
        if (!$this->isAllowedForApiAccount($ticket)) {
            return $this->errorNotFound();
        }

        return $this->respondWithItem($ticket, new TicketTransformer());
    }

    public function apiDestroy(Ticket $ticket)
    {
        $api_account = $this->api_account;

        if (!$api_account->isSystemAccount()) {
            return $this->errorNotFound();
        }

        return $this->respondWithItem($ticket, new TicketTransformer());
    }

    /**
     * sync status values between AbuseIO instances.
     *
     * @param TicketFormRequest $ticketForm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSyncStatus(TicketFormRequest $ticketForm)
    {
        $localTicket = null;

        // we receive the remote AbuseIO ticket so we lookup the matching
        // local ticket
        $remoteTicket = new Ticket();
        $remoteTicket->fill($ticketForm->all());
        $localTicket = Ticket::where('remote_api_token', '=', $remoteTicket->api_token)->first();

        if (!$localTicket) {
            return $this->errorNotFound('No matching local ticket found');
        }

        // parent status overrules child
        $localTicket->status_id = $remoteTicket->status_id;

        // if we are the leaf of the tree, also set contact_status_id
        if (!$localTicket->hasChild()) {
            switch ($remoteTicket->status_id) {
                case 'IGNORED':
                    $localTicket->contact_status_id = 'IGNORED';
                    break;
                case 'CLOSED':
                case 'RESOLVED':
                    $localTicket->contact_status_id = 'RESOLVED';
                    break;
                case 'OPEN':
                case 'ESCALATED':
                default:
                    $localTicket->contact_status_id = 'OPEN';
                    break;
            }
        }

        // save the ticket
        $localTicket->save();

        // add a new hidden note, to inform that the ticket is synced
        $note = new Note();
        $note->ticket_id = $localTicket->id;
        $note->viewed = false;
        $note->hidden = true;
        $note->submitter = 'AbuseIO';
        $note->text = trans('misc.note_text_status_sync_parent');

        $note->save();

        return $this->respondWithItem($localTicket, new TicketTransformer());
    }

    /**
     * sync contact status values between AbuseIO instances.
     *
     * @param TicketFormRequest $ticketForm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSyncContactStatus(TicketFormRequest $ticketForm)
    {
        $localTicket = null;

        // we receive the remote AbuseIO ticket so we lookup the matching
        // local ticket

        $remoteTicket = new Ticket();
        $remoteTicket->fill($ticketForm->all());
        $localTicket = Ticket::where('remote_api_token', '=', $remoteTicket->api_token)->first();

        if (!$localTicket) {
            return $this->errorNotFound('No matching local ticket found');
        }

        // update local contact_status_id
        switch ($remoteTicket->status_id) {
            case 'IGNORED':
                $localTicket->contact_status_id = 'IGNORED';
                break;
            case 'CLOSED':
            case 'RESOLVED':
                $localTicket->contact_status_id = 'RESOLVED';
                break;
            case 'OPEN':
            case 'ESCALATED':
            default:
                $localTicket->contact_status_id = 'OPEN';
                break;
        }

        // save the ticket
        $localTicket->save();

        // add a new hidden note, to inform that the ticket is synced
        $note = new Note();
        $note->ticket_id = $localTicket->id;
        $note->viewed = false;
        $note->hidden = true;
        $note->submitter = 'AbuseIO';
        $note->text = trans('misc.note_text_status_sync_child');

        $note->save();

        return $this->respondWithItem($localTicket, new TicketTransformer());
    }

    /**
     * Update the requested contact information.
     *
     * @param Ticket $ticket
     * @param string $only
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Ticket $ticket, $only = null)
    {
        TicketUpdate::contact($ticket, $only);

        return Redirect::route('admin.tickets.show', $ticket->id)
            ->with('message', 'Contact has been updated.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TicketFormRequest $ticketForm
     * @param Ticket            $ticket
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apiUpdate(TicketFormRequest $ticketForm, Ticket $ticket)
    {
        if (!$this->isAllowedForApiAccount($ticket)) {
            return $this->errorNotFound();
        }

        $ticket->update($ticketForm->all());

        return $this->respondWithItem($ticket, new TicketTransformer());
    }

    /**
     * Set the status of a tickets.
     *
     * @param Ticket $ticket
     * @param string $newstatus
     *
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
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notify(Ticket $ticket, $only = null)
    {
        $notification = new Notification();
        $notification->walkList(
            $notification->buildList($ticket->id, false, true, $only)
        );

        return Redirect::route('admin.tickets.show', $ticket->id)
            ->with('message', 'Contact has been notified.');
    }

    /**
     * Send a notification for this ticket to the contacts.
     * api method.
     *
     * @param Ticket $ticket
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiNotify(Ticket $ticket)
    {
        if (!$this->isAllowedForApiAccount($ticket)) {
            return $this->errorNotFound();
        }

        $notification = new Notification();
        $notification->walkList(
            $notification->buildList($ticket->id, false, true, null)
        );

        // refresh ticket
        $ticket = Ticket::find($ticket->id);

        return $this->respondWithItem($ticket, new TicketTransformer());
    }

    /**
     * anonymize the ticket.
     *
     * @param Ticket $ticket
     * @param string $email
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiAnonymize(Ticket $ticket, $email, $randomness)
    {
        if (!$this->isAllowedForApiAccount($ticket)) {
            return $this->errorNotFound();
        }

        $updated = $ticket->anonymize($email, $randomness);

        return $this->respondWithItem($updated, new TicketTransformer());
    }

    /**
     * Returns true when the current API account is allowed to access the
     * given ticket. The system account may access every ticket; any other
     * account may only access tickets where it is the IP or domain contact.
     *
     * @param Ticket $ticket
     *
     * @return bool
     */
    protected function isAllowedForApiAccount(Ticket $ticket)
    {
        $account = $this->api_account;

        if ($account === null) {
            return false;
        }

        if ($account->isSystemAccount()) {
            return true;
        }

        return (int) $ticket->ip_contact_account_id === (int) $account->id
            || (int) $ticket->domain_contact_account_id === (int) $account->id;
    }
}
