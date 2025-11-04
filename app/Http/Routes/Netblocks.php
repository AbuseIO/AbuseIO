<?php

use AbuseIO\Http\Controllers\NetblocksController;

Route::group(
    [
        'prefix' => 'netblocks',
        'as'     => 'netblocks.',
    ],
    function () {
        // Search netblock
        Route::get('search/{one?}/{two?}/{three?}', [NetblocksController::class, 'search'])
            ->middleware('permission:netblocks_view')
            ->name('search');

        // Access to index list
        Route::get('', [NetblocksController::class, 'index'])
            ->middleware('permission:netblocks_view')
            ->name('index');

        // Access to show object
        Route::get('{netblocks}', [NetblocksController::class, 'show'])
            ->middleware('permission:netblocks_view')
            ->name('show');

        // Access to export object
        Route::get('export/{format}', [NetblocksController::class, 'export'])
            ->middleware('permission:netblocks_export')
            ->name('export');

        // Access to create object
        Route::get('create', [NetblocksController::class, 'create'])
            ->middleware('permission:netblocks_create')
            ->name('create');
        Route::post('', [NetblocksController::class, 'store'])
            ->middleware('permission:netblocks_create')
            ->name('store');

        // Access to edit object
        Route::get('{netblocks}/edit', [NetblocksController::class, 'edit'])
            ->middleware('permission:netblocks_edit')
            ->name('edit');
        Route::match(['put', 'patch'], '{netblocks}', [NetblocksController::class, 'update'])
            ->middleware('permission:netblocks_edit')
            ->name('update');

        // Access to delete object
        Route::delete('/{netblocks}', [NetblocksController::class, 'destroy'])
            ->middleware('permission:netblocks_delete')
            ->name('destroy');
    }
);
