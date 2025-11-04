<?php
use AbuseIO\Http\Controllers\IncidentsController;

Route::group(
    [
        'prefix'     => 'incidents',
        'as'         => 'incidents.',
        'middleware' => ['apiaccountavailable', 'apisystemaccount'],
    ],
    function () {
        Route::post('', [IncidentsController::class, 'apiStore'])->name('store');
    }
);
