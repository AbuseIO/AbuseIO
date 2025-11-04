<?php
use AbuseIO\Http\Controllers\AccountsController;

// Model binding is centralized in RouteServiceProvider

Route::group(
    [
        'prefix' => 'accounts',
        'as'     => 'accounts.',
    ],
    function () {
        // Search contacts
        Route::get('search/{one?}/{two?}/{three?}/{four?}/{five?}', [AccountsController::class, 'search'])
            ->middleware('permission:accounts_view')
            ->name('search');

        // Access to index list
        Route::get('', [AccountsController::class, 'index'])
            ->middleware('permission:accounts_view')
            ->name('index');

        // Access to show object
        Route::get('{accounts}', [AccountsController::class, 'show'])
            ->middleware('permission:accounts_view')
            ->name('show');

        // Access to export object
        Route::get('export/{format}', [AccountsController::class, 'export'])
            ->middleware('permission:accounts_export')
            ->name('export');

        // Access to create object
        Route::get('create', [AccountsController::class, 'create'])
            ->middleware('permission:accounts_create')
            ->name('create');
        Route::post('', [AccountsController::class, 'store'])
            ->middleware('permission:accounts_create')
            ->name('store');

        // Access to disable object
        Route::get('{accounts}/disable', [AccountsController::class, 'disable'])
            ->middleware('permission:accounts_disable')
            ->name('disable');

        // Access to enable object
        Route::get('{accounts}/enable', [AccountsController::class, 'enable'])
            ->middleware('permission:accounts_enable')
            ->name('enable');

        // Access to edit object
        Route::get('{accounts}/edit', [AccountsController::class, 'edit'])
            ->middleware('permission:accounts_edit')
            ->name('edit');
        Route::match(['put', 'patch'], '{accounts}', [AccountsController::class, 'update'])
            ->middleware('permission:accounts_edit')
            ->name('update');

        // Access to delete object
        Route::delete('/{accounts}', [AccountsController::class, 'destroy'])
            ->middleware('permission:accounts_delete')
            ->name('destroy');
    }
);
