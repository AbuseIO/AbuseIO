<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use AbuseIO\Http\Requests;
use AbuseIO\Http\Requests\SearchFormRequest;
use AbuseIO\Http\Controllers\Controller;
use AbuseIO\Models\Ticket;
use AbuseIO\Models\Event;
use Lang;

class SearchController extends Controller
{

    /*
     * Call the parent constructor to generate a base ACL
     */
    public function __construct()
    {
        parent::__construct('createDynamicACL');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $classifications = array_merge([Lang::get('misc.all')], array_column(Lang::get('classifications'), 'name'));
        $types = array_merge([Lang::get('misc.all')], array_column(Lang::get('types.type'), 'name'));
        $status = array_merge([Lang::get('misc.all')], array_column(Lang::get('types.status'), 'name'));
        $state = array_merge([Lang::get('misc.all')], array_column(Lang::get('types.state'), 'name'));

        return view('search')
            ->with('classification_selection', $classifications)
            ->with('type_selection', $types)
            ->with('status_selection', $status)
            ->with('state_selection', $state)
            ->with('user', $request->user());
    }
}
