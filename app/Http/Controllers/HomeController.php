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
    public function index(Request $request)
    {
        $user = $request->user();

        return view('home', ['user' => $user]);
    }
}
