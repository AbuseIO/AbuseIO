<?php

namespace AbuseIO\Http\Middleware;

use AbuseIO\Traits\Api;
use Closure;

class ApiAccountAvailable
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
        if (is_null($request->api_account)) {
            return $this->errorForbidden('Access Forbidden');
        }

        return $next($request);
    }
}
