<?php

use AbuseIO\Http\Controllers\IncidentsController;

Route::group(
    [
        'prefix'     => 'incidents',
        'as'         => 'incidents.',
        'middleware' => ['api.account', 'api.system'],
    ],
    function () {
        Route::post('', [IncidentsController::class, 'apiStore'])->name('store');
    }
);
