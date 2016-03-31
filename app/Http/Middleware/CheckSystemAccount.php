<?php

namespace AbuseIO\Http\Middleware;

use Auth;
use Closure;

class CheckSystemAccount
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $account = Auth::user()->account;

        if (!$account->isSystemAccount()) {
            return back()->with('message', 'This action is only allowed for the system account');
        }

        return $next($request);
    }
}
