<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        return view(
            'home',
            [
                'user' => $this->user
            ]
        );
    }
}
