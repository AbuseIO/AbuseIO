<?php

namespace AbuseIO\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;
use URL;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $user = false;

    public function __construct()
    {
        // Globalize user information
        $user = Auth::user();
        if ($user) {
            $this->user = $user;
        }

    }
}
