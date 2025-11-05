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

        // Access to show object (id must be numeric)
        Route::get('{domains}', [DomainsController::class, 'show'])
            ->middleware('permission:domains_view')
            ->where('domains', '[0-9]+')
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

        // Access to edit object (id must be numeric)
        Route::get('{domains}/edit', [DomainsController::class, 'edit'])
            ->middleware('permission:domains_edit')
            ->where('domains', '[0-9]+')
            ->name('edit');
        Route::match(['put', 'patch'], '{domains}', [DomainsController::class, 'update'])
            ->middleware('permission:domains_edit')
            ->where('domains', '[0-9]+')
            ->name('update');

        // Access to delete object (id must be numeric)
        Route::delete('/{domains}', [DomainsController::class, 'destroy'])
            ->middleware('permission:domains_delete')
            ->where('domains', '[0-9]+')
            ->name('destroy');
    }
);
