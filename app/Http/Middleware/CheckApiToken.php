<?php

namespace AbuseIO\Http\Middleware;

use AbuseIO\Models\Account;
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
        $api_token = $request->header('X_API_TOKEN');

        // no token given
        if (is_null($api_token)) {
            return $this->errorForbidden('No api token');
        }

        // find a matching account a save it in the request
        $api_account = Account::where('token', $api_token)->first();
        if (!$api_account) {
            return $this->errorUnauthorized('Unauthorized token');
        }

        $request->api_account = $api_account;

        return $next($request);
    }
}
