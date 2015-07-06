<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\SearchFormRequest;

use AbuseIO\Http\Controllers\Controller;

use AbuseIO\Models\Ticket;
use AbuseIO\Models\Event;

class SearchController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('search');
    }
}
