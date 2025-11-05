<?php

namespace AbuseIO\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::check()) {
            if ($request->user()->cando($permission)) {
                return $next($request);
            }
        }

        // Build detailed forbidden context
        $routeName = $request->route() ? $request->route()->getName() : null;
        $routeUri = $request->route() ? $request->route()->uri() : $request->path();
        // Do not collect object identifiers to avoid leaking model info

        $message = 'Forbidden: missing required permission.';
        
        if (!empty($permission)) {
            $message .= " Missing permission: {$permission}";
        } else {
            $message .= ' No permission specified for this route.';
        }

        if ($routeName) {
            $message .= " | Route: {$routeName}";
        }
        if ($routeUri) {
            $message .= " | URI: {$routeUri}";
        }
        // Intentionally omit object identifiers from the message

        // If AJAX/JSON request, return structured JSON; otherwise render 403 view with context
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(
                [
                    'error' => 'forbidden',
                    'message' => $message,
                    'permission' => $permission,
                    'route' => $routeName,
                    'uri' => $routeUri,
                ],
                403
            );
        }

        // If we don't have the permission 'login_portal' and it is requested redirect to logout
        if ($permission === 'login_portal') {
            // Return a 403 instead of redirecting to avoid confusing loops
            return response()->view('errors.403', [
                'message' => $message,
                'permission' => $permission,
                'route' => $routeName,
                'uri' => $routeUri,
            ], 403);
        }

        // If we are redirecting back to the current page then return a 403 error instead of looping
        if (strpos(back(), '>'.$request->fullUrl().'</a>') !== false) {
            return response()->view('errors.403', [
                'message' => $message,
                'permission' => $permission,
                'route' => $routeName,
                'uri' => $routeUri,
            ], 403);
        }

        // Default: render 403 view with detailed context
        return response()->view('errors.403', [
            'message' => $message,
            'permission' => $permission,
            'route' => $routeName,
            'uri' => $routeUri,
        ], 403);
    }
}
