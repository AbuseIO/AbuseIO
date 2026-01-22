
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            // Load modern routes files
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::prefix('api')->middleware('api')
                ->group(base_path('routes/api.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Web middleware group
        $middleware->web(append: [
            \AbuseIO\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \AbuseIO\Http\Middleware\VerifyCsrfToken::class,
            \AbuseIO\Http\Middleware\Locale::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Register middleware aliases
        $middleware->alias([
            'api.enabled' => \AbuseIO\Http\Middleware\ApiEnabled::class,
            'api.account' => \AbuseIO\Http\Middleware\ApiAccountAvailable::class,
            'api.system' => \AbuseIO\Http\Middleware\ApiSystemAccount::class,
            'api.enabled' => \AbuseIO\Http\Middleware\ApiEnabled::class,
            'api.token' => \AbuseIO\Http\Middleware\CheckApiToken::class,
            'ash.token' => \AbuseIO\Http\Middleware\CheckAshToken::class,
            'auth' => \AbuseIO\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'check.account' => \AbuseIO\Http\Middleware\CheckAccount::class,
            'check.system' => \AbuseIO\Http\Middleware\CheckSystemAccount::class,
            'guest' => \AbuseIO\Http\Middleware\RedirectIfAuthenticated::class,
            'locale' => \AbuseIO\Http\Middleware\Locale::class,
            'note.submitter' => \AbuseIO\Http\Middleware\AppendNoteSubmitter::class,
            'appendnotesubmitter' => \AbuseIO\Http\Middleware\AppendNoteSubmitter::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'permission' => \AbuseIO\Http\Middleware\CheckPermission::class,
            'precognition' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
