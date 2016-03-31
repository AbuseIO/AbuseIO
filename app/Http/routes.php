<?php

Route::controllers(
    [
        'auth'     => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]
);

// Redirect the default page to the dashboard (or login)
Route::get(
    '/',
    function () {
        return Redirect::to('/admin/home');
    }
);

/*
 * Admin routes
 */
Route::group(
    [
        'prefix'     => 'admin',
        'middleware' => [
            'auth',
            'permission:login_portal',
        ],
        'as' => 'admin.',
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

        // Version check
        Route::get(
            '/version',
            [
                'as'   => 'version',
                'uses' => 'HomeController@version',
            ]
        );

        // Contacts
        require app_path().'/Http/Routes/Contacts.php';

        // Netblocks
        require app_path().'/Http/Routes/Netblocks.php';

        // Domains
        require app_path().'/Http/Routes/Domains.php';

        // Tickets
        require app_path().'/Http/Routes/Tickets.php';

        // Evidence
        require app_path().'/Http/Routes/Evidence.php';

        // Notes
        require app_path().'/Http/Routes/Notes.php';

        // Analytics
        require app_path().'/Http/Routes/Analytics.php';

        // Settings related
        require app_path().'/Http/Routes/SettingsAccounts.php';
        require app_path().'/Http/Routes/SettingsBrands.php';
        require app_path().'/Http/Routes/SettingsUsers.php';
        require app_path().'/Http/Routes/Profile.php';

    }
);

/*
 * Ash routes
 */
Route::group(
    [
        'prefix' => 'ash',
        'as'     => 'ash.',
    ],
    function () {
        Route::get(
            'collect/{ticketID}/{token}',
            [
                'as'         => 'show',
                'uses'       => 'AshController@index',
                'middleware' => [
                    'ash.token',
                ],
            ]
        );

        Route::post(
            'collect/{ticketID}/{token}',
            [
                'as'         => 'update',
                'uses'       => 'AshController@addNote',
                'middleware' => [
                    'ash.token',
                ],
            ]
        );

        // Language switcher
        Route::get(
            'locale/{locale?}',
            [
                'as'         => 'setlocale',
                'uses'       => 'LocaleController@setLocale',
                'middleware' => [
                    //
                ],
            ]
        );

        // Logos
        Route::get(
            'logo/{id}',
            [
                'as'         => 'logo',
                'uses'       => 'BrandsController@logo',
                'middleware' => [
                    //
                ],
            ]
        );
    }
);
