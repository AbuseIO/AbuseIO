<?php

return [
    /*
   |--------------------------------------------------------------------------
   | Application Environment
   |--------------------------------------------------------------------------
   |
   | This value determines the "environment" your application is currently
   | running in. This may determine how you prefer to configure various
   | services your application utilizes. Set this in your ".env" file.
   |
   */
    'env' => env('APP_ENV', 'production'),

    'name'    => 'AbuseIO',
    'version' => '4.3.0',

    /*
    |--------------------------------------------------------------------------
    | Application filesystem/environment permissions
    |--------------------------------------------------------------------------
    |
    | By default installations should be using a dedicated user 'abuseio' with
    | the group 'abuseio' on the system. However in some case you cannot use
    | these names and with the below settings you can override the defaults
    |
    */

    'user'  => env('APP_LOCALUSER', 'abuseio'),
    'group' => env('APP_LOCALGROUP', 'abuseio'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG'),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone'    => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Date & Time format
    |--------------------------------------------------------------------------
    |
    | See http://php.net/manual/en/function.date.php for more information
    | about setting your own format.
    */

    // Implement ISO 8601 usage
    'date_format' => 'c',
    // or use this for more human readable:
    // 'date_format' => 'd-m-Y H:i:s P',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale'  => 'en',

    // 'locale' => ['Language Name', 'flag'],
    'locales' => [
        'en' => ['English', 'gb'],
        'nl' => ['Nederlands', 'nl'],
        'gr' => ['Ελληνικά', 'gr'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY', 'SomeRandomString'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Installation ID
    |--------------------------------------------------------------------------
    |
    | This key is used to identify this installation when using inter-AbuseIO
    | communications with one or more instances. Once set you should never
    | change this ID unless you are very sure!
    |
    */

    'id' => env('APP_ID', 'DEFAULT'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */

        //        'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider', -- removed upgrading from 5.2

        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        /*
         * Some plugin Service Providers ...
         */
        Wpb\String_Blade_Compiler\StringBladeServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,

        /*
         * Package Service Providers...
         */
        Laravel\Tinker\TinkerServiceProvider::class,

        /*
         * Application Service Providers...
         */
        AbuseIO\Providers\AppServiceProvider::class,
        // AbuseIO\Providers\BroadcastServiceProvider::class,
        AbuseIO\Providers\ConfigServiceProvider::class,
        AbuseIO\Providers\ContactServiceProvider::class,
        AbuseIO\Providers\HelperServiceProvider::class,
        AbuseIO\Providers\EventServiceProvider::class,
        AbuseIO\Providers\RouteServiceProvider::class,
        AbuseIO\Providers\SystemAdminManagerProvider::class,
        AbuseIO\Providers\ValidationsServiceProvider::class,

        Yajra\DataTables\DataTablesServiceProvider::class,

        /*
         * Developer Providers ...
         */
        Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App'          => Illuminate\Support\Facades\App::class,
        'Arr'          => Illuminate\Support\Arr::class,
        'Artisan'      => Illuminate\Support\Facades\Artisan::class,
        'Auth'         => Illuminate\Support\Facades\Auth::class,
        'Blade'        => Illuminate\Support\Facades\Blade::class,
        'Bus'          => Illuminate\Support\Facades\Bus::class,
        'Cache'        => Illuminate\Support\Facades\Cache::class,
        'Config'       => Illuminate\Support\Facades\Config::class,
        'Cookie'       => Illuminate\Support\Facades\Cookie::class,
        'Crypt'        => Illuminate\Support\Facades\Crypt::class,
        'DataTables'   => Yajra\DataTables\Facades\DataTables::class,
        'DB'           => Illuminate\Support\Facades\DB::class,
        'Eloquent'     => Illuminate\Database\Eloquent\Model::class,
        'Event'        => Illuminate\Support\Facades\Event::class,
        'File'         => Illuminate\Support\Facades\File::class,
        'Gate'         => Illuminate\Support\Facades\Gate::class,
        'Hash'         => Illuminate\Support\Facades\Hash::class,
        'Inspiring'    => Illuminate\Foundation\Inspiring::class,
        'Lang'         => Illuminate\Support\Facades\Lang::class,
        'Log'          => Illuminate\Support\Facades\Log::class,
        'Mail'         => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password'     => Illuminate\Support\Facades\Password::class,
        'Queue'        => Illuminate\Support\Facades\Queue::class,
        'Redirect'     => Illuminate\Support\Facades\Redirect::class,
        'Redis'        => Illuminate\Support\Facades\Redis::class,
        'Request'      => Illuminate\Support\Facades\Request::class,
        'Response'     => Illuminate\Support\Facades\Response::class,
        'Route'        => Illuminate\Support\Facades\Route::class,
        'Schema'       => Illuminate\Support\Facades\Schema::class,
        'Session'      => Illuminate\Support\Facades\Session::class,
        'Storage'      => Illuminate\Support\Facades\Storage::class,
        'Str'          => Illuminate\Support\Str::class,
        'URL'          => Illuminate\Support\Facades\URL::class,
        'Validator'    => Illuminate\Support\Facades\Validator::class,
        'View'         => Illuminate\Support\Facades\View::class,
        'Form'         => Collective\Html\FormFacade::class,
        'Html'         => Collective\Html\HtmlFacade::class,
        'Uuid'         => Webpatser\Uuid\Uuid::class,
        'Carbon'       => Carbon\Carbon::class,
    ],

];
