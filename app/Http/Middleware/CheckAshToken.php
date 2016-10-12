<?php

namespace AbuseIO\Http\Middleware;

use AbuseIO\Models\Ticket;
use Closure;

/**
 * Class CheckAshToken.
 */
class CheckAshToken
{
    /**
     * @param $request
     * @param Closure $next
     *
     * @throws \Exception
     *
     * @return \BladeView|bool|\Illuminate\Contracts\Routing\ResponseFactory
     *                                                                       \Illuminate\Contracts\View\Factory
     *                                                                       \Illuminate\View\View
     *                                                                       \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        $ticketID = $request->ticketID;
        $token = $request->token;

        $ticket = Ticket::find($ticketID);

        if (!empty($ticket)) {

            // retrieve ash tokens from the ticket, so we can check our token against them
            $validTokenIP = $ticket->ash_token_ip;
            $validTokenDomain = $ticket->ash_token_domain;

            if ($token == $validTokenIP) {
                $request->merge(['AshAuthorisedBy' => 'TokenIP']);

                return $next($request);
            } elseif ($token == $validTokenDomain) {
                $request->merge(['AshAuthorisedBy' => 'TokenDomain']);

                return $next($request);
            }
        }

        return $request->ajax ? response('Unauthorized.', 401) : view('errors.403');
    }
}
