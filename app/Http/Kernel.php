<?php

namespace AbuseIO\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Class Kernel.
 */
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        // -added to make ModelRouteBinding work;
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \AbuseIO\Http\Middleware\EncryptCookies::class,
            // \AbuseIO\AbuseIOInstaller\Middleware\installOnFirstRun::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \AbuseIO\Http\Middleware\VerifyCsrfToken::class,
            \AbuseIO\Http\Middleware\Locale::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

        ],
        'api' => [
            'throttle:60,1',
            'bindings',
        ],
        'install' => [
            //
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                => \AbuseIO\Http\Middleware\Authenticate::class,
        'auth.basic'          => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'            => \Illuminate\Routing\Middleware\SubstituteBindings::class, //-- was added laravel 5.3
        //        'can'                 => \Illuminate\Auth\Middleware\Authorize::class, -- should be added because laravel 5.3 but i think we dont use it we use cando on the user
        'guest'               => \AbuseIO\Http\Middleware\RedirectIfAuthenticated::class,
        'permission'          => \AbuseIO\Http\Middleware\CheckPermission::class,
        'ash.token'           => \AbuseIO\Http\Middleware\CheckAshToken::class,
        'checkaccount'        => \AbuseIO\Http\Middleware\CheckAccount::class,
        'checksystemaccount'  => \AbuseIO\Http\Middleware\CheckSystemAccount::class,
        'apienabled'          => \AbuseIO\Http\Middleware\ApiEnabled::class,
        'checkapitoken'       => \AbuseIO\Http\Middleware\CheckApiToken::class,
        'apiaccountavailable' => \AbuseIO\Http\Middleware\ApiAccountAvailable::class,
        'apisystemaccount'    => \AbuseIO\Http\Middleware\ApiSystemAccount::class,
        'appendnotesubmitter' => \AbuseIO\Http\Middleware\AppendNoteSubmitter::class,
    ];
}
