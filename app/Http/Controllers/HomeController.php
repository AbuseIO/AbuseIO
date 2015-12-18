<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view(
            'home',
            [
                'auth_user' => $this->auth_user,
            ]
        );
    }
}
