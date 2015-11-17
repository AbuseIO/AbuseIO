<?php
Route::model('tickets', 'AbuseIO\Models\Ticket');
Route::resource('tickets', 'TicketsController');

Route::group(
    [
        'prefix' => 'tickets',
        'as' => 'tickets.',
    ],
    function () {
        // Search Tickets
        Route::get(
            'search/{one?}/{two?}/{three?}/{four?}/{five?}',
            [
                'middleware' => 'permission:tickets_view',
                'as' => 'search',
                'uses' => 'TicketsController@search'
            ]
        );

        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:tickets_view',
                'as' => 'index',
                'uses' => 'TicketsController@index'
            ]
        );

        // Access to show object
        route::get(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_view',
                'as' => 'show',
                'uses' => 'TicketsController@show'
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:tickets_export',
                'as' => 'export',
                'uses' => 'TicketsController@export'
            ]
        );

        // Access to create object
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

        // Access to edit object
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

        // Access to delete object
        route::delete(
            '/{tickets}',
            [
                'middleware' => 'permission:tickets_delete',
                'as' => 'destroy',
                'uses' => 'TicketsController@destroy'
            ]
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
