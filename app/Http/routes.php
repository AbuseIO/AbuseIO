<?php
Route::controllers(
    [
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]
);

// Redirect the default page to the dashboard (or login)
Route::get(
    "/",
    function () {
        return Redirect::to('/admin/home');
    }
);

// Ash routes
Route::group(
    [
        'prefix' => 'ash',
    ],
    function () {
        Route::get('collect/{ticketID}/{token}', 'AshController@index');

        // Language switcher
        Route::get('locale/{locale?}', 'LocaleController@setLocale');

        // Logos
        Route::get('logo/{id}', 'BrandsController@logo');
    }
);

// Api routes
Route::group(
    [
        'prefix' => 'api',
        'middleware' => [
            'auth',
            'acl:api_login'
        ],
    ],
    function () {

        //

    }
);

// Admin routes
Route::group(
    [
        'prefix' => 'admin',
        'middleware' => [
            'auth',
            'acl:admin_login'
        ],
    ],
    function () {

        // Language switcher
        Route::get('locale/{locale?}', 'LocaleController@setLocale');

        // Brands logo display
        Route::get('logo/{id}', 'BrandsController@logo');

        // Dashboard
        Route::get(
            'home',
            'HomeController@index'
        );

        // Contacts
        Route::resource('contacts', 'ContactsController');
        Route::model('contacts', 'AbuseIO\Models\Contact');
        require app_path() . '/Http/Routes/Contacts.php';

        // Netblocks
        Route::resource('netblocks', 'NetblocksController');
        Route::model('netblocks', 'AbuseIO\Models\Netblock');
        require app_path() . '/Http/Routes/Netblocks.php';

        // Domains
        Route::resource('domains', 'DomainsController');
        Route::model('domains', 'AbuseIO\Models\Domain');
        require app_path() . '/Http/Routes/Domains.php';

        // Tickets
        Route::model('tickets', 'AbuseIO\Models\Ticket');
        Route::resource('tickets', 'TicketsController');
        require app_path() . '/Http/Routes/Tickets.php';

        // Search
        require app_path() . '/Http/Routes/Search.php';

        // Analytics
        require app_path() . '/Http/Routes/Analytics.php';

        // Settings related
        Route::model('accounts', 'AbuseIO\Models\Account');
        Route::resource('accounts', 'AccountsController');
        require app_path() . '/Http/Routes/SettingsAccounts.php';

        Route::model('brands', 'AbuseIO\Models\Brand');
        Route::resource('brands', 'BrandsController');
        require app_path() . '/Http/Routes/SettingsBrands.php';

        Route::model('users', 'AbuseIO\Models\User');
        Route::resource('users', 'UsersController');
        require app_path() . '/Http/Routes/SettingsUsers.php';

        Route::resource('profile', 'ProfilesController');
        require app_path() . '/Http/Routes/Profiles.php';

    }
);
