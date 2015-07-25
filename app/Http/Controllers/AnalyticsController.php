<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Models\Ticket;
use Lang;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $classCounts = [ ];

        foreach (Lang::get('classifications') as $classID => $classInfo) {
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
            ->with('classCounts', $classCounts);
    }
}
