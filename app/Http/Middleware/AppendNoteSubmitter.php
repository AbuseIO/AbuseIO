<?php

namespace AbuseIO\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class AppendNoteSubmitter
{
    /**
     * @param $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // merge the submitter

        if (config('main.notes.show_abusedesk_names') === true) {
            $postingUser = ' ('.Auth::user()->fullName().')';
        } else {
            $postingUser = '';
        }

        switch ($request->method()) {
            case 'POST':
                $request->merge(
                    [
                        'submitter' => trans('ash.communication.abusedesk').$postingUser,
                        'viewed'    => true,
                    ]
                );
                break;
            case 'PATCH':
            case 'PUT':
                $request->merge(
                    [
                        'submitter' => trans('ash.communication.abusedesk').$postingUser,
                    ]
                );
                break;
            default:
                break;
        }

        return $next($request);
    }
}
