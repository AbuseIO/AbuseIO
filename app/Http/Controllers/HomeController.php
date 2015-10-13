<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Http\Request;
use AbuseIO\Http\Requests;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view('home', ['user' => $user, 'account' => $user->account]);
    }
}
