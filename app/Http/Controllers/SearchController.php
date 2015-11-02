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
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $classifications = array_combine(
            array_keys(
                trans('classifications')
            ),
            array_column(
                trans('classifications'),
                'name'
            )
        );
        $classifications = [0 => trans('misc.all')]+$classifications;
        $types = array_merge([trans('misc.all')], array_column(trans('types.type'), 'name'));
        $status = array_merge([trans('misc.all')], array_column(trans('types.status'), 'name'));
        $state = array_merge([trans('misc.all')], array_column(trans('types.state'), 'name'));

        return view('search')
            ->with('classification_selection', $classifications)
            ->with('type_selection', $types)
            ->with('status_selection', $status)
            ->with('state_selection', $state)
            ->with('auth_user', $this->auth_user);
    }
}
