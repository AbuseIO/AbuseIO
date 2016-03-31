<?php

namespace AbuseIO\Http\Controllers;

use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
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
