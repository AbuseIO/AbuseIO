<?php

namespace AbuseIO\Http\Middleware;

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
        //todo: check the token
        $token=$request->route()->parameters()['apitoken'];

        //dd($token);
        return $next($request);
    }
}
