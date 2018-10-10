<?php

namespace AbuseIO\Http\Middleware;

use AbuseIO\Models\Account;
use AbuseIO\Models\Ticket;
use AbuseIO\Traits\Api;
use Closure;

class CheckApiToken
{
    use Api;

    /**
     * @param $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // read the token
        $api_token = $request->header('X-API-TOKEN');

        // no token given
        if (is_null($api_token)) {
            return $this->errorForbidden('No api token');
        }

        // find a matching account / ticket and save it in the request
        $api_account = Account::where('token', $api_token)->first();
        $api_ticket = Ticket::where('api_token', $api_token)->first();

        if (!$api_account && !$api_ticket) {
            return $this->errorUnauthorized('Unauthorized token');
        }

        $request->merge([
            'api_account' => $api_account,
            'api_ticket'  => $api_ticket,
        ]);

        return $next($request);
    }
}
