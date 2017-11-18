<?php

namespace AbuseIO\Http\Middleware;

use AbuseIO\Traits\Api;
use Closure;

class ApiSystemAccount
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
        if (!$request->api_account->isSystemAccount()) {
            return $this->errorForbidden('Access Forbidden, only the system account has access');
        }

        return $next($request);
    }
}
