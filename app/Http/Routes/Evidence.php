<?php

use AbuseIO\Http\Controllers\EvidenceController;

// Model binding is centralized in RouteServiceProvider

Route::group(
    [
        'prefix' => 'evidence',
        'as'     => 'evidence.',
    ],
    function () {
        /*
         * Index evidence
         */
        Route::get('', [EvidenceController::class, 'index'])
            ->middleware('permission:evidence_view')
            ->name('index');

        /*
        | Show evidence
        */
        Route::get('{evidence}', [EvidenceController::class, 'show'])
            ->middleware('permission:evidence_view')
            ->name('show');

        /*
        | Download evidence
        */
        Route::get('{evidence}/download', [EvidenceController::class, 'download'])
            ->middleware('permission:evidence_view')
            ->name('download');

        Route::get('{evidence}/attachment/{file}', [EvidenceController::class, 'attachment'])
            ->middleware('permission:evidence_view')
            ->name('attachment');
    }
);
