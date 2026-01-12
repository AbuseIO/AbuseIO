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
        Route::get('create', [IncidentsController::class, 'create'])
            ->middleware('permission:incidents_create')
            ->name('create');

        Route::post('', [IncidentsController::class, 'store'])
            ->middleware('permission:incidents_create')
            ->name('store');
    }
);
