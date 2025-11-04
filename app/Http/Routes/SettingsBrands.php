<?php
use AbuseIO\Http\Controllers\BrandsController;

// Model binding is centralized in RouteServiceProvider

Route::group(
    [
        'prefix' => 'brands',
        'as'     => 'brands.',
    ],
    function () {
        // Search contacts
        Route::get('search/{one?}/{two?}/{three?}/{four?}/{five?}', [BrandsController::class, 'search'])
            ->middleware('permission:brands_view')
            ->name('search');

        // Access to index list
        Route::get('', [BrandsController::class, 'index'])
            ->middleware('permission:brands_view')
            ->name('index');

        // Access to show object
        Route::get('{brands}', [BrandsController::class, 'show'])
            ->middleware('permission:brands_view')
            ->name('show');

        // Access to export object
        Route::get('export/{format}', [BrandsController::class, 'export'])
            ->middleware('permission:brands_export')
            ->name('export');

        // Access to create object
        Route::get('create', [BrandsController::class, 'create'])
            ->middleware('permission:brands_create')
            ->name('create');
        Route::post('', [BrandsController::class, 'store'])
            ->middleware('permission:brands_create')
            ->name('store');

        // Access to edit object
        Route::get('{brands}/edit', [BrandsController::class, 'edit'])
            ->middleware('permission:brands_edit')
            ->name('edit');
        // Access to activate object
        Route::get('{brands}/activate', [BrandsController::class, 'activate'])
            ->middleware('permission:brands_edit')
            ->name('activate');
        Route::match(['put', 'patch'], '{brands}', [BrandsController::class, 'update'])
            ->middleware('permission:brands_edit')
            ->name('update');

        // Access to delete object
        Route::delete('/{brands}', [BrandsController::class, 'destroy'])
            ->middleware('permission:brands_delete')
            ->name('destroy');
    }
);
