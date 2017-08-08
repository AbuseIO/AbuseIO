<?php

Route::group(
    [
        'prefix' => 'incidents',
        'as'     => 'incidents.',
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
