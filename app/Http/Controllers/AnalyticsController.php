<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Models\Ticket;
use AbuseIO\Models\TicketGraphPoint;
use Illuminate\Http\Request;
use Lang;

/**
 * Class AnalyticsController.
 */
class AnalyticsController extends Controller
{
    /**
     * AnalyticsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $classCounts = [];

        foreach ((array) Lang::get('classifications') as $classID => $classInfo) {
            $classTotal = new \stdClass();

            $tickets = Ticket::where('class_id', $classID);
            $ticketCount = $tickets->count();
            $tickets = $tickets->get();

            if ($ticketCount !== 0) {
                $classTotal->name = $classInfo['name'];
                $classTotal->tickets = $tickets->count();

                $classCounts[] = $classTotal;
            }
        }

        return view('analytics')
            ->with('classCounts', $classCounts)
            ->with('new', TicketGraphPoint::getStatistics('created_at'))
            ->with('updated', TicketGraphPoint::getStatistics('updated_at'))
            ->with('class_options', TicketGraphPoint::getDistinctFiltersFor('class'))
            ->with('type_options', TicketGraphPoint::getDistinctFiltersFor('type'))
            ->with('status_options', TicketGraphPoint::getDistinctFiltersFor('status'))
            ->with('lifecycle_options', TicketGraphPoint::getFiltersForLifecycle())
            ->with('auth_user', $this->auth_user);
    }

    public function show(Request $request)
    {
        $getParams = [
            'lifecycle' => $request->get('s_lifecycle') ?: 'created_at',
            'from'      => $request->get('s_from') ?: false,
            'till'      => $request->get('s_till') ?: false,
            'class'     => $request->get('s_classification') ?: false,
            'status'    => $request->get('s_status') ?: false,
            'type'      => $request->get('s_type') ?: false,
        ];

        $methodName = 'getDataFor';
        $params = [];

        foreach ($getParams as $key => $value) {
            if ($value) {
                $methodName .= ucfirst($key);
                $params[] = $value;
            }
        }

        return TicketGraphPoint::forwardCallToApi($methodName, $params);
    }
}
