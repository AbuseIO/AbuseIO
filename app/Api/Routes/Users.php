<?php
use AbuseIO\Http\Controllers\UsersController;

Route::group(
    [
        'prefix'     => 'users',
        'as'         => 'users.',
        'middleware' => ['api.account', 'api.system'],
    ],
    function () {
        // Access to index list
        Route::get('', [UsersController::class, 'apiIndex'])->name('index');

        // Access to show object
        Route::get('{users}', [UsersController::class, 'apiShow'])->name('show');
    }
);
