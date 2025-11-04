<?php
use AbuseIO\Http\Controllers\IncidentsController;

Route::group(
    [
        'prefix' => 'incidents',
        'as'     => 'incidents.',
    ],
    function () {
        /*
        | Create incident
        */
        Route::get(
            'create',
            [
                'middleware' => 'permission:incidents_create',
                'as'         => 'create',
                'uses'       => [IncidentsController::class, 'create'],
            ]
        );
        Route::post(
            '',
            [
                'middleware' => 'permission:incidents_create',
                'as'         => 'store',
                'uses'       => [IncidentsController::class, 'store'],
            ]
        );
    }
);
