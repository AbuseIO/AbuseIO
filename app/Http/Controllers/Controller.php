<?php

namespace AbuseIO\Http\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;

/**
 * Class Controller.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $auth_user = false;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // Globalize user information
        $user = Auth::user();
        if ($user) {
            $this->auth_user = $user;
            Session::put('locale', $user->locale);
        }
    }
}
