<?php
use AbuseIO\Http\Controllers\NetblocksController;

Route::group(
    [
        'prefix'     => 'netblocks',
        'as'         => 'netblocks.',
        'middleware' => ['apiaccountavailable', 'apisystemaccount'],
    ],
    function () {
        Route::get('search/{type}/{param}', [NetblocksController::class, 'apiSearch'])->name('search');

        Route::get('', [NetblocksController::class, 'apiIndex'])->name('index');

        Route::get('{netblocks}', [NetblocksController::class, 'apiShow'])->name('show');

        Route::delete('{netblocks}', [NetblocksController::class, 'apiDestroy'])->name('delete');

        Route::post('', [NetblocksController::class, 'apiStore'])->name('store');

        Route::put('{netblocks}', [NetblocksController::class, 'apiUpdate'])->name('update');
    }
);
