<?php
use AbuseIO\Http\Controllers\DomainsController;

Route::group(
    [
        'prefix'     => 'domains',
        'as'         => 'domains.',
        'middleware' => ['api.account', 'api.system'],
    ],
    function () {
        Route::get('', [DomainsController::class, 'apiIndex'])->name('index');

        Route::get('{domains}', [DomainsController::class, 'apiShow'])->name('show');

        Route::delete('{domains}', [DomainsController::class, 'apiDestroy'])->name('delete');

        Route::post('', [DomainsController::class, 'apiStore'])->name('store');

        Route::put('{domains}', [DomainsController::class, 'apiUpdate'])->name('update');
    }
);
