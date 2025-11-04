<?php

use AbuseIO\Http\Controllers\AnalyticsController;
Route::group(
    [
        'prefix' => 'analytics',
        'as'     => 'analytics.',
    ],
    function () {
        // Access to index list
        Route::get('', [AnalyticsController::class, 'index'])
            ->middleware('permission:analytics_view')
            ->name('index');
        Route::get('graph', [AnalyticsController::class, 'show'])
            ->middleware('permission:analytics_view')
            ->name('graph');
    }
);
