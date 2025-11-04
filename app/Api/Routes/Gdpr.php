<?php
use AbuseIO\Http\Controllers\GdprController;

Route::group(
    [
        'prefix'     => 'gdpr',
        'as'         => 'gdpr.',
        'middleware' => ['api.account', 'api.system'],
    ],
    function () {
        Route::get('anonymize/{email}', [GdprController::class, 'apiAnonymize'])->name('anonymize');
    }
);
