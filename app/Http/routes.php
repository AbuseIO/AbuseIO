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
            'auth.basic',
            'permission:login_api'
        ],
    ],
    function () {

        // TODO

    }
);

// Admin routes
Route::group(
    [
        'prefix' => 'admin',
        'middleware' => [
            'auth',
            'auth.basic',
            'permission:login_portal'
        ],
    ],
    function () {

        // Language switcher
        Route::get('locale/{locale?}', 'LocaleController@setLocale');

        // Brands logo display
        Route::get('logo/{id}', 'BrandsController@logo');

        // Dashboard
        Route::get(
            '/',
            function () {
                return Redirect::to('/admin/home');
            }
        );
        Route::get('/home', 'HomeController@index');

        // Contacts
        require app_path() . '/Http/Routes/Contacts.php';

        // Netblocks
        require app_path() . '/Http/Routes/Netblocks.php';

        // Domains
        require app_path() . '/Http/Routes/Domains.php';

        // Tickets
        require app_path() . '/Http/Routes/Tickets.php';

        // Search
        require app_path() . '/Http/Routes/Search.php';

        // Analytics
        require app_path() . '/Http/Routes/Analytics.php';

        // Settings related
        require app_path() . '/Http/Routes/SettingsAccounts.php';

        require app_path() . '/Http/Routes/SettingsBrands.php';


        require app_path() . '/Http/Routes/SettingsUsers.php';

        require app_path() . '/Http/Routes/Profiles.php';

    }
);
