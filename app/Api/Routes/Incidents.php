<?php

Route::group(
    [
        'prefix'     => 'incidents',
        'as'         => 'incidents.',
        'middleware' => ['apiaccountavailable', 'apisystemaccount'],
    ],
    function () {
        Route::post(
            '',
            [
                'as'   => 'store',
                'uses' => 'IncidentsController@apiStore',
            ]
        );
    }
);
