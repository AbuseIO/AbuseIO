<?php

namespace AbuseIO\Http\Controllers;

use AbuseIO\Http\Requests;

/**
 * Class HomeController
 * @package AbuseIO\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
