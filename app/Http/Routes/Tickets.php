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
        Route::get(
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
        Route::get(
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
        Route::get(
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
        Route::get(
            'create',
            [
                'middleware' => 'permission:tickets_create',
                'as' => 'create',
                'uses' => 'TicketsController@create'
            ]
        );
        Route::post(
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
        Route::get(
            '{tickets}/edit',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => 'edit',
                'uses' => 'TicketsController@edit'
            ]
        );
        Route::patch(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => 'update',
                'uses' => 'TicketsController@update'
            ]
        );
        Route::put(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => 'update',
                'uses' => 'TicketsController@update'
            ]
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
        | Contact information
        */
        Route::group(
            [
                'prefix' => '{tickets}/update'
            ],
            function () {
                Route::get(
                    '{who?}',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'update',
                        'uses' => 'TicketsController@update'
                    ]
                );
            }
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
                    '{who?}',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'notify',
                        'uses' => 'TicketsController@notify'
                    ]
                );
            }
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
                    '{status}',
                    [
                        'middleware' => 'permission:tickets_edit',
                        'as' => 'status',
                        'uses' => 'TicketsController@status'
                    ]
                );
            }
        );

        /*
        | Evidence view
        */
        Route::group(
            [
                'prefix' => '{tickets}/evidence'
            ],
            function () {
                Route::get(
                    '{id}/view',
                    [
                        'middleware' => 'permission:tickets_view',
                        'as' => 'evidence.view',
                        'uses' => 'TicketsController@viewEvidence'
                    ]
                );
            }
        );

        /*
        | Evidence view
        */
        Route::group(
            [
                'prefix' => '{tickets}/evidence'
            ],
            function () {
                Route::get(
                    '{id}/view/attachment/{file}',
                    [
                        'middleware' => 'permission:tickets_view',
                        'as' => 'evidence.attachment',
                        'uses' => 'TicketsController@downloadEvidenceAttachment'
                    ]
                );
            }
        );

        /*
        | Evidence download
        */
        Route::group(
            [
                'prefix' => '{tickets}/evidence'
            ],
            function () {
                Route::get(
                    '{id}/download',
                    [
                        'middleware' => 'permission:tickets_view',
                        'as' => 'evidence.download',
                        'uses' => 'TicketsController@downloadEvidence'
                    ]
                );
            }
        );
    }
);
