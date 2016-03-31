<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Models\Ticket;
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
            ->with('auth_user', $this->auth_user);
    }
}
