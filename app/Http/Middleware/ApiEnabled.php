<?php
/**
 * Created by PhpStorm.
 * User: jover
 * Date: 20-5-16
 * Time: 11:05
 */

namespace AbuseIO\Http\Middleware;

use AbuseIO\Traits\Api;
use Closure;
use Log;

class ApiEnabled
{
    use Api;

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return a 403 when the api is disabled
        if (!config("main.api.enabled"))
        {
            return $this->errorForbidden('API is disabled');
        }

        return $next($request);
    }
}