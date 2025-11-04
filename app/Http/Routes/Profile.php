<?php

use AbuseIO\Http\Controllers\ProfileController;

Route::prefix('profile')->as('profile.')->group(function () {
    // Access to index list
    Route::get('', [ProfileController::class, 'edit'])
        ->middleware('permission:profile_manage')
        ->name('index');

    // Access to edit object
    Route::match(['put', 'patch'], '{profile}', [ProfileController::class, 'update'])
        ->middleware('permission:profile_manage')
        ->name('update');
});
