<?php

use AbuseIO\Http\Controllers\DomainsController;

Route::group(
    [
        'prefix' => 'domains',
        'as'     => 'domains.',
    ],
    function () {
        // Search domains
        Route::get('search/{one?}/{two?}', [DomainsController::class, 'search'])
            ->middleware('permission:domains_view')
            ->name('search');

        // Access to index list
        Route::get('', [DomainsController::class, 'index'])
            ->middleware('permission:domains_view')
            ->name('index');

        // Access to show object
        Route::get('{domains}', [DomainsController::class, 'show'])
            ->middleware('permission:domains_view')
            ->name('show');

        // Access to export object
        Route::get('export/{format}', [DomainsController::class, 'export'])
            ->middleware('permission:domains_export')
            ->name('export');

        // Access to create object
        Route::get('create', [DomainsController::class, 'create'])
            ->middleware('permission:domains_create')
            ->name('create');
        Route::post('', [DomainsController::class, 'store'])
            ->middleware('permission:domains_create')
            ->name('store');

        // Access to edit object
        Route::get('{domains}/edit', [DomainsController::class, 'edit'])
            ->middleware('permission:domains_edit')
            ->name('edit');
        Route::match(['put', 'patch'], '{domains}', [DomainsController::class, 'update'])
            ->middleware('permission:domains_edit')
            ->name('update');

        // Access to delete object
        Route::delete('/{domains}', [DomainsController::class, 'destroy'])
            ->middleware('permission:domains_delete')
            ->name('destroy');
    }
);
