<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use AbuseIO\Models\Ticket;
use Illuminate\Http\Request;
use Lang;

class AnalyticsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
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
            ->with('classCounts', $classCounts)
            ->with('auth_user', $this->auth_user);
    }
}
