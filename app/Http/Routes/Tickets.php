<?php
Route::model('tickets', 'AbuseIO\Models\Ticket');
Route::resource('tickets', 'TicketsController');

Route::group(
    [
        'prefix' => 'tickets',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:tickets_view',
                'as' => 'admin.tickets.index',
                'uses' => 'TicketsController@index'
            ]
        );

        // Access to show object
        route::get(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_view',
                'as' => 'admin.tickets.show',
                'uses' => 'TicketsController@show'
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:tickets_export',
                'as' => 'admin.tickets.export',
                'uses' => 'TicketsController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:tickets_create',
                'as' => 'admin.tickets.create',
                'uses' => 'TicketsController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:tickets_create',
                'as' => 'admin.tickets.store',
                'uses' => 'TicketsController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{tickets}/edit',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => 'admin.tickets.edit',
                'uses' => 'TicketsController@edit'
            ]
        );
        route::patch(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => '',
                'uses' => 'TicketsController@update'
            ]
        );
        route::put(
            '{tickets}',
            [
                'middleware' => 'permission:tickets_edit',
                'as' => 'admin.tickets.update',
                'uses' => 'TicketsController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{tickets}',
            [
                'middleware' => 'permission:tickets_delete',
                'as' => 'admin.tickets.destroy',
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
                        'as' => 'admin.tickets.showOpen',
                        'uses' => 'TicketsController@statusOpen'
                    ]
                );
                Route::get(
                    'closed',
                    [
                        'middleware' => 'permission:tickets_view',
                        'as' => 'admin.tickets.showClosed',
                        'uses' => 'TicketsController@showClosed'
                    ]
                );
            }
        );

    }
);
