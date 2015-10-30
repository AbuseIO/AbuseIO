<?php

Route::group(
    [
        'prefix' => 'tickets',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'acl:admin_tickets_view',
                'as' => 'admin.tickets.index',
                'uses' => 'TicketsController@index'
            ]
        );

        // Access to show object
        route::get(
            '{tickets}',
            [
                'middleware' => 'acl:admin_tickets_view',
                'as' => 'admin.tickets.show',
                'uses' => 'TicketsController@show'
            ]
        );

        // Access to export object
        route::get(
            'export',
            [
                'middleware' => 'acl:admin_tickets_export2',
                'as' => 'admin.tickets.export',
                'uses' => 'TicketsController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'acl:admin_tickets_create',
                'as' => 'admin.tickets.create',
                'uses' => 'TicketsController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'acl:admin_tickets_create',
                'as' => 'admin.tickets.store',
                'uses' => 'TicketsController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{tickets}/edit',
            [
                'middleware' => 'acl:admin_tickets_edit',
                'as' => 'admin.tickets.edit',
                'uses' => 'TicketsController@edit'
            ]
        );
        route::patch(
            '{tickets}',
            [
                'middleware' => 'acl:admin_tickets_edit',
                'as' => '',
                'uses' => 'TicketsController@update'
            ]
        );
        route::put(
            '{tickets}',
            [
                'middleware' => 'acl:admin_tickets_edit',
                'as' => 'admin.tickets.update',
                'uses' => 'TicketsController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{tickets}',
            [
                'middleware' => 'acl:admin_tickets_delete',
                'as' => 'admin.tickets.destroy',
                'uses' => 'TicketsController@destroy'
            ]
        );

        // Filter options
        // TODO: replace with search filter instead of method? Currently no ACL's applied to these resources!
        Route::group(
            [
                'prefix' => 'status'
            ],
            function () {
                Route::resource('open', 'TicketsController@statusOpen');
                Route::resource('closed', 'TicketsController@statusClosed');
            }
        );

    }
);
