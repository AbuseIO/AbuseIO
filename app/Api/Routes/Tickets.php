<?php
use AbuseIO\Http\Controllers\TicketsController;

Route::group(
    [
        'prefix' => 'tickets',
        'as'     => 'tickets.',
    ],
    function () {
        Route::get('', [TicketsController::class, 'apiIndex'])
            ->middleware(['api.account'])
            ->name('index');

        Route::post('search', [TicketsController::class, 'apiSearch'])
            ->middleware(['api.account'])
            ->name('search');

        Route::post('syncstatus', [TicketsController::class, 'apiSyncStatus'])
            ->name('syncstatus');

        Route::post('synccontactstatus', [TicketsController::class, 'apiSyncContactStatus'])
            ->name('synccontactstatus');

        Route::get('{tickets}', [TicketsController::class, 'apiShow'])
            ->middleware(['api.account'])
            ->name('show');

        Route::delete('{tickets}', [TicketsController::class, 'apiDestroy'])
            ->middleware(['api.account'])
            ->name('delete');

        Route::post('', [TicketsController::class, 'apiStore'])
            ->middleware(['api.account'])
            ->name('store');

        Route::put('{tickets}', [TicketsController::class, 'apiUpdate'])
            ->middleware(['api.account'])
            ->name('update');

        Route::get('{tickets}/notify', [TicketsController::class, 'apiNotify'])
            ->middleware(['api.account'])
            ->name('notify');

        Route::get('{tickets}/anonymize/{email}/{randomness}', [TicketsController::class, 'apiAnonymize'])
            ->middleware(['api.account'])
            ->name('anonymize');
    }
);
