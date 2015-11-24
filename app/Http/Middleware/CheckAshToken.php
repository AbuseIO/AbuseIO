<?php namespace AbuseIO\Http\Middleware;

use Closure;
use Request;
use AbuseIO\Models\Ticket;
use Uuid;

class CheckAshToken
{
    public function handle($request, Closure $next)
    {
        /*
         * TODO: find how laravel passes $ticketID and $token from the controller to middleware
         * this will remove the need of parsing the URI ourselves
         */
        $uri = explode('/', Request::path());
        if ($uri[0] === 'ash' && $uri['1'] === 'collect') {
            $ticketID = $uri[2];
            $token = $uri[3];

            $ticket = Ticket::find($ticketID);

            if (!empty($ticket)) {
                // Prevent unauthorized access by UNDEF contact tokens(default)
                $validTokenIP = md5(Uuid::generate(4));
                $validTokenDomain = md5(Uuid::generate(4));

                if ($ticket->ip_contact_reference != 'UNDEF') {
                    $validTokenIP = md5($ticket->id . $ticket->ip . $ticket->ip_contact_reference);
                }
                if ($token == $validTokenIP) {
                    $request->merge(['AshAuthorisedBy' => 'TokenIP']);
                    return $next($request);
                }

                if ($ticket->domain_contact_reference != 'UNDEF') {
                    $request->merge(['AshAuthorisedBy' => 'TokenDomain']);
                    $validTokenDomain = md5($ticket->id . $ticket->domain . $ticket->domain_contact_reference);
                }
                if ($token == $validTokenDomain) {
                    return $next($request);
                }

            }
        }

        return $request->ajax ? response('Unauthorized.', 401) : view('errors.403');
    }
}
