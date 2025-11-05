<?php

use AbuseIO\Http\Controllers\ContactsController;

Route::group(
    [
        'prefix'     => 'contacts',
        'as'         => 'contacts.',
        'middleware' => ['api.account', 'api.system'],
    ],
    function () {
        Route::get('', [ContactsController::class, 'apiIndex'])->name('index');

        Route::get('{contacts}', [ContactsController::class, 'apiShow'])->name('show');

        Route::delete('{contacts}', [ContactsController::class, 'apiDestroy'])->name('delete');

        Route::get('search/{email}', [ContactsController::class, 'apiSearch'])->name('search');

        Route::get('{contacts}/anonymize/{randomness}', [ContactsController::class, 'apiAnonymize'])->name('anonymize');

        Route::post('', [ContactsController::class, 'apiStore'])->name('store');

        Route::put('{contacts}', [ContactsController::class, 'apiUpdate'])->name('update');
    }
);
