<?php

namespace AbuseIO\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

/**
 * Class RouteServiceProvider.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'AbuseIO\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        // Define API rate limiter using modern style
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // Centralized route model bindings
        Route::model('contacts', \AbuseIO\Models\Contact::class);
        Route::model('netblocks', \AbuseIO\Models\Netblock::class);
        Route::model('domains', \AbuseIO\Models\Domain::class);
        Route::model('tickets', \AbuseIO\Models\Ticket::class);
        Route::model('evidence', \AbuseIO\Models\Evidence::class);
        Route::model('users', \AbuseIO\Models\User::class);
        Route::model('brands', \AbuseIO\Models\Brand::class);
        Route::model('accounts', \AbuseIO\Models\Account::class);
        Route::model('notes', \AbuseIO\Models\Note::class);

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        // Routing is configured via bootstrap/app.php using modern bootstrapping.
        // No additional mapping required here to avoid double registration.
    }
}
