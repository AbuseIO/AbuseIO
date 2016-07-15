<?php

Route::group(
    [
        'prefix' => 'tickets',
        'as'     => 'tickets.',
    ],
    function () {
        route::get(
            '',
            [
                'as'         => 'index',
                'uses'       => 'TicketsController@apiIndex',
            ]
        );

        Route::get(
            '{tickets}',
            [
                'as'         => 'show',
                'uses'       => 'TicketsController@apiShow',
            ]
        );

        Route::delete(
            '{tickets}',
            [
                'as'         => 'delete',
                'uses'       => 'TicketsController@apiDestroy',
            ]
        );

        Route::post(
            '',
            [
                'as'         => 'store',
                'uses'       => 'TicketsController@apiStore',
            ]
        );

        Route::put(
            '{tickets}',
            [
                'as'   => 'update',
                'uses' => 'TicketsController@apiUpdate',
            ]
        );
    }
);
