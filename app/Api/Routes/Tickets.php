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
                'middleware' => ['apiaccountavailable'],
            ]
        );

        Route::post(
            'search',
            [
                'as'         => 'search',
                'uses'       => 'TicketsController@apiSearch',
                'middleware' => ['apiaccountavailable'],
            ]
        );

        Route::post(
            'syncstatus',
            [
                'as'   => 'syncstatus',
                'uses' => 'TicketsController@apiSyncStatus',
            ]
        );

        Route::post(
            'synccontactstatus',
            [
                'as'   => 'synccontactstatus',
                'uses' => 'TicketsController@apiSyncContactStatus',
            ]
        );

        Route::get(
            '{tickets}',
            [
                'as'         => 'show',
                'uses'       => 'TicketsController@apiShow',
                'middleware' => ['apiaccountavailable'],
            ]
        );

        Route::delete(
            '{tickets}',
            [
                'as'         => 'delete',
                'uses'       => 'TicketsController@apiDestroy',
                'middleware' => ['apiaccountavailable'],
            ]
        );

        Route::post(
            '',
            [
                'as'         => 'store',
                'uses'       => 'TicketsController@apiStore',
                'middleware' => ['apiaccountavailable'],
            ]
        );

        Route::put(
            '{tickets}',
            [
                'as'         => 'update',
                'uses'       => 'TicketsController@apiUpdate',
                'middleware' => ['apiaccountavailable'],
            ]
        );

        Route::get(
            '{tickets}/notify',
            [
                'as'         => 'notify',
                'uses'       => 'TicketsController@apiNotify',
                'middleware' => ['apiaccountavailable'],
            ]
        );

        Route::get(
            '{tickets}/anonymize/{email}/{randomness}',
            [
                'as'         => 'anonymize',
                'uses'       => 'TicketsController@apiAnonymize',
                'middleware' => ['apiaccountavailable'],
            ]
        );
    }
);
