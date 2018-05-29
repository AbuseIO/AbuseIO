<?php

Route::group(
    [
        'prefix'     => 'contacts',
        'as'         => 'contacts.',
        'middleware' => ['apiaccountavailable', 'apisystemaccount'],
    ],
    function () {
        Route::get(
            '',
            [
                'as'   => 'index',
                'uses' => 'ContactsController@apiIndex',
            ]
        );

        Route::get(
            '{contacts}',
            [
                'as'   => 'show',
                'uses' => 'ContactsController@apiShow',
            ]
        );

        Route::delete(
            '{contacts}',
            [
                'as'   => 'delete',
                'uses' => 'ContactsController@apiDestroy',
            ]
        );

        Route::get(
            'search/{email}',
            [
                'as'   => 'search',
                'uses' => 'ContactsController@apiSearch',
            ]
        );

        Route::get(
            '{contacts}/anonymize/{randomness}',
            [
                'as'   => 'anonymize',
                'uses' => 'ContactsController@apiAnonymize',
            ]
        );

        Route::post(
            '',
            [
                'as'   => 'store',
                'uses' => 'ContactsController@apiStore',
            ]
        );

        Route::put(
            '{contacts}',
            [
                'as'   => 'update',
                'uses' => 'ContactsController@apiUpdate',
            ]
        );
    }
);
