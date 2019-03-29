<?php

namespace AbuseIO\Http\Middleware;

use Auth;
use Closure;

/**
 * Class CheckPermission.
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $permission
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {
        if (!app('Illuminate\Contracts\Auth\Guard')->guest()) {
            if ($request->user()->cando($permission)) {
                return $next($request);
            }
        }

        Auth::logout();
        $message = 'Sorry! You are not authorized to access that resource and have been logged out.'.
            " Missing permission : {$permission}";

        $request->session()->flash(
            'message',
            $message
        );

        // If we don't have the permission 'login_portal' and it is requested redirect to logout
        if ($permission === 'login_portal') {
            return redirect('/auth/login')
                ->with(['message', $message]);
        }

        // If we are redirecting back to the current page then return a 403 error instead of looping
        if (strpos(back(), '>'.$request->fullUrl().'</a>') !== false) {
            abort(403);
        }

        // If not authorized then return a 401 for AJAX or redirect back with a message
        return $request->ajax ? response('Unauthorized.', 401) : redirect()->back();
    }
}
