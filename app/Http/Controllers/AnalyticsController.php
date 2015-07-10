<?php namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;

class AnalyticsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        return view('analytics');

    }
}
