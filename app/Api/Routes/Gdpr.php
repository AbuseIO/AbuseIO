<?php
use AbuseIO\Http\Controllers\GdprController;

Route::group(
    [
        'prefix'     => 'gdpr',
        'as'         => 'gdpr.',
        'middleware' => ['apiaccountavailable', 'apisystemaccount'],
    ],
    function () {
        Route::get('anonymize/{email}', [GdprController::class, 'apiAnonymize'])->name('anonymize');
    }
);
