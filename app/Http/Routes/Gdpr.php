<?php

use AbuseIO\Http\Controllers\GdprController;

Route::group(
    [
        'prefix' => 'gdpr',
        'as'     => 'gdpr.',
    ],
    function () {
        // Access to edit object
        Route::post('{contacts}', [GdprController::class, 'anonymize'])
            ->middleware('permission:contacts_edit')
            ->name('anonymize');
    }
);
