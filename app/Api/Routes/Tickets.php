<?php
use AbuseIO\Http\Controllers\TicketsController;

Route::group(
    [
        'prefix' => 'tickets',
        'as'     => 'tickets.',
    ],
    function () {
        Route::get('', [TicketsController::class, 'apiIndex'])
            ->middleware(['apiaccountavailable'])
            ->name('index');

        Route::post('search', [TicketsController::class, 'apiSearch'])
            ->middleware(['apiaccountavailable'])
            ->name('search');

        Route::post('syncstatus', [TicketsController::class, 'apiSyncStatus'])
            ->name('syncstatus');

        Route::post('synccontactstatus', [TicketsController::class, 'apiSyncContactStatus'])
            ->name('synccontactstatus');

        Route::get('{tickets}', [TicketsController::class, 'apiShow'])
            ->middleware(['apiaccountavailable'])
            ->name('show');

        Route::delete('{tickets}', [TicketsController::class, 'apiDestroy'])
            ->middleware(['apiaccountavailable'])
            ->name('delete');

        Route::post('', [TicketsController::class, 'apiStore'])
            ->middleware(['apiaccountavailable'])
            ->name('store');

        Route::put('{tickets}', [TicketsController::class, 'apiUpdate'])
            ->middleware(['apiaccountavailable'])
            ->name('update');

        Route::get('{tickets}/notify', [TicketsController::class, 'apiNotify'])
            ->middleware(['apiaccountavailable'])
            ->name('notify');

        Route::get('{tickets}/anonymize/{email}/{randomness}', [TicketsController::class, 'apiAnonymize'])
            ->middleware(['apiaccountavailable'])
            ->name('anonymize');
    }
);
