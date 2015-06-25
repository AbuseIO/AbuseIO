<?php
App::setLocale(Config::get('main.interface.language'));

Route::group(
    [
    'prefix' => 'admin'
    ],
    function () {

        Route::get(
            '/',
            function () {
                return Redirect::to('/admin/home');
            }
        );
        Route::get('home', 'HomeController@index');

        Route::model('contacts', 'AbuseIO\Models\Contact');
        Route::resource('contacts', 'ContactsController');
        Route::get(
            'export/contacts',
            [
                'as' => 'admin.export.contacts',
                'uses' => 'ContactsController@export',
            ]
        );

        Route::model('netblocks', 'AbuseIO\Models\Netblock');
        Route::resource('netblocks', 'NetblocksController');
        Route::get(
            '/export/netblocks',
            [
                'as' => 'admin.export.netblocks',
                'uses' => 'NetblocksController@export',
            ]
        );

        Route::model('domains', 'AbuseIO\Models\Domain');
        Route::resource('domains', 'DomainsController');
        Route::get(
            '/export/domains',
            [
                'as' => 'admin.export.domains',
                'uses' => 'DomainsController@export',
            ]
        );

        Route::model('tickets', 'AbuseIO\Models\Ticket');
        Route::resource('tickets', 'TicketsController');
        Route::get(
            '/export/tickets',
            [
                'as' => 'admin.export.tickets',
                'uses' => 'TicketsController@export',
            ]
        );

        Route::get('search', 'SearchController@index');

        Route::get('analytics', 'AnalyticsController@index');

    }
);

Route::group(
    [
    'prefix' => 'ash',
    ],
    function () {
        Route::get('/collect/{ticketID}/{token}', 'AshController@index');
    }
);

Route::group(
    [
    'prefix' => 'api',
    ],
    function () {

    }
);