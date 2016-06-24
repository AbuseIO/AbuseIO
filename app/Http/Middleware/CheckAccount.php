<?php

namespace AbuseIO\Http\Middleware;

use Auth;
use Closure;
use Log;

/**
 * Class CheckAccount.
 */
class CheckAccount
{
    const IDSEGMENT = 3;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param $model
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $model)
    {
        $account = null;

        // gather info
        $model = '\AbuseIO\Models\\'.$model;
        $auth_user = Auth::user();

        if (!is_null($auth_user)) {
            // web ui
            $account = $auth_user->account;
        }
        else
        {
            // api
            $account = $request->api_account;
        }

        // try to retrieve the id of the model (by getting it out the request segment or out the input)
        $model_id = $request->segment(self::IDSEGMENT);
        if (empty($model_id)) {
            $model_id = $request->input('id');
        }

        // sanity checks on the model_id
        if (!empty($model_id) and preg_match('/\d+/', $model_id)) {
            // only check if the checkAccountAccess exists

            if (method_exists($model, 'checkAccountAccess')) {
                if (!$model::checkAccountAccess($model_id, $account)) {
                    // if the checkAccountAccess() fails return to the last page
                    return back()->with('message', 'Account ['.$account->name.'] is not allowed to access this object');
                }
            } else {
                Log::notice(
                    "CheckAccount Middleware is called for {$model}, which doesn't have a checkAccountAccess method"
                );
            }
        } else {
            Log::notice("CheckAccount Middleware is called, with model_id [{$model_id}] for {$model}, which doesn't match the model_id format");
        }

        return $next($request);
    }
}
