<?php

namespace AbuseIO\Http\Middleware;

use Closure;
use Config;
use Illuminate\Http\Request;
use Session;

/**
 * Class Locale.
 */
class Locale
{
    /**
     * @var array
     */
    protected $languages = ['en'];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->languages = array_keys(Config::get('app.locales'));

        if (!Session::has('locale')) {
            Session::put('locale', $request->getPreferredLanguage($this->languages));
        }
        app()->setLocale(Session::get('locale'));

        return $next($request);
    }
}
