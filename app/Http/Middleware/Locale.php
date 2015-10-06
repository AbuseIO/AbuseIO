<?php namespace AbuseIO\Http\Middleware;

use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Routing\Middleware;
use Closure;
use Session;
use Config;

class Locale implements Middleware
{
    protected $languages = ['en'];

    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->languages = array_keys(Config::get('app.locales'));

        if (!Session::has('locale')) {
            Session::put('locale', $request->getPreferredLanguage($this->languages));
        }
        $this->app->setLocale(Session::get('locale'));

        return $next($request);
    }
}
