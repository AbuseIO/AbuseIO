<?php

Route::group(
    [
        'prefix' => 'auth',
    ],
    function () {
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');
        // Password Reset Routes...
        Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    }
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
        Route::post('verifyexternalapi', 'ApiDomainCheckerController@store');

        // Api key generator;
        Route::post('apikey', function () {
            return response()->json(['data' => generateApiToken()]);
        });

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

        // Incidents
        require app_path().'/Http/Routes/Incidents.php';

        // Evidence
        require app_path().'/Http/Routes/Evidence.php';

        // Notes
        require app_path().'/Http/Routes/Notes.php';

        // Analytics
        require app_path().'/Http/Routes/Analytics.php';

        // GDPR
        require app_path().'/Http/Routes/Gdpr.php';

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
                    'web',
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
                    'web',
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

/*
 * API routes group
 */

Route::get('api/getversioninfo', function () {
    return response()->json(['version' => 'v1']);
});

Route::group(
    [
        'prefix'     => 'api',
        'as'         => 'api.',
        'middleware' => ['apienabled', 'checkapitoken'],
    ],
    function ($group) {
        Route::group(
            [
                'prefix' => 'v1',
                'as'     => 'v1.',
            ],
            function () {
                // Evidence
                //require app_path().'/Api/Routes/Evidence.php';

                // Analytics
                //require app_path().'/Api/Routes/Analytics.php';

                require app_path().'/Api/Routes/Accounts.php';
                require app_path().'/Api/Routes/Brands.php';
                require app_path().'/Api/Routes/Contacts.php';
                require app_path().'/Api/Routes/Domains.php';
                require app_path().'/Api/Routes/Netblocks.php';
                require app_path().'/Api/Routes/Notes.php';
                require app_path().'/Api/Routes/Tickets.php';
                require app_path().'/Api/Routes/Users.php';
                require app_path().'/Api/Routes/Incidents.php';
                require app_path().'/Api/Routes/Gdpr.php';
            }
        );
    }
);
