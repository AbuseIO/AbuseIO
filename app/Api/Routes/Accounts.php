<?php
use AbuseIO\Http\Controllers\AccountsController;

Route::group(
    [
        'prefix'     => 'accounts',
        'as'         => 'accounts.',
        'middleware' => ['api.account', 'api.system'],
    ],
    function () {
        // Access to index list
        Route::get('', [AccountsController::class, 'apiIndex'])->name('index');

        // Access to show object
        Route::get('{accounts}', [AccountsController::class, 'apiShow'])->name('show');

        Route::delete('{accounts}', [AccountsController::class, 'apiDestroy'])->name('delete');

        Route::post('', [AccountsController::class, 'apiStore'])->name('store');

        Route::put('{accounts}', [AccountsController::class, 'apiUpdate'])->name('update');
    }
);
