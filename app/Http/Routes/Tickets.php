<?php
Route::model('tickets', 'AbuseIO\Models\Ticket');
Route::resource('tickets', 'TicketsController');

Route::group(
    [
        'prefix' => 'tickets',
        'as' => 'tickets.',
    ],
    function () {
        /*
        | Ticket search
        */
        Route::get(
            'search/{one?}/{two?}/{three?}/{four?}/{five?}',
            [
                'middleware' => 'permission:tickets_view',
                'as' => 'search',
                'uses' => 'TicketsController@search'
            ]
        );

        /*
        | Index tickets
        */
        route::get(
            '',
            [
                'middleware' => 'permission:tickets_view',
                'as' => 'index',
                'uses' => 'TicketsController@index'
            ]
        );

        /*
        | Show ticket
        */
        route::get(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_view',
                'as' => 'show',
                'uses' => 'TicketsController@show'
            ]
        );

        /*
        | Export tickets
        */
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:tickets_export',
                'as' => 'export',
                'uses' => 'TicketsController@export'
            ]
        );

        /*
        | Create ticket
        */
        route::get(
            'create',
            [
                'middleware' => 'permission:tickets_create',
                'as' => 'create',
                'uses' => 'TicketsController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:tickets_create',
                'as' => 'store',
                'uses' => 'TicketsController@store'
            ]
        );

        /*
        | Edit ticket
        */
        route::get(
            '{tickets}/edit',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => 'edit',
                'uses' => 'TicketsController@edit'
            ]
        );
        route::patch(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => 'update',
                'uses' => 'TicketsController@update'
            ]
        );
        route::put(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => 'update',
                'uses' => 'TicketsController@update'
            ]
        );

        /*
        | Edit ticket status
        */
        Route::group(
            [
                'prefix' => '{tickets}/status'
            ],
            function () {
                Route::get(
                    'solved',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'status.solved',
                        'uses' => 'TicketsController@status'
                    ]
                );
                Route::get(
                    'close',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'status.close',
                        'uses' => 'TicketsController@status'
                    ]
                );
                Route::get(
                    'ignore',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'status.ignore',
                        'uses' => 'TicketsController@status'
                    ]
                );
            }
        );


        /*
        | Delete ticket
        */
        route::delete(
            '/{tickets}',
            [
                'middleware' => 'permission:tickets_delete',
                'as' => 'destroy',
                'uses' => 'TicketsController@destroy'
            ]
        );

        /*
        | Notifications
        */
        Route::group(
            [
                'prefix' => '{tickets}/notify'
            ],
            function () {
                Route::get(
                    'ip',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'ip',
                        'uses' => 'TicketsController@notify'
                    ]
                );
                Route::get(
                    'domain',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'domain',
                        'uses' => 'TicketsController@notify'
                    ]
                );
                Route::get(
                    'both',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'both',
                        'uses' => 'TicketsController@notify'
                    ]
                );
            }
        );

        /*
        | Update contact information
        */
        Route::group(
            [
                'prefix' => '{tickets}/update'
            ],
            function () {
                Route::get(
                    'ip',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'update.ip',
                        'uses' => 'TicketsController@updatecontact'
                    ]
                );
                Route::get(
                    'domain',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'update.domain',
                        'uses' => 'TicketsController@updatecontact'
                    ]
                );
                Route::get(
                    'both',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'update.both',
                        'uses' => 'TicketsController@updatecontact'
                    ]
                );
            }
        );

        // Filter options
        // TODO: replace with search filter instead of method?
        Route::group(
            [
                'prefix' => 'status'
            ],
            function () {
                Route::get(
                    'open',
                    [
                        'middleware' => 'permission:tickets_view',
                        'as' => 'showOpen',
                        'uses' => 'TicketsController@statusOpen'
                    ]
                );
                Route::get(
                    'closed',
                    [
                        'middleware' => 'permission:tickets_view',
                        'as' => 'showClosed',
                        'uses' => 'TicketsController@showClosed'
                    ]
                );
            }
        );

    }
);
