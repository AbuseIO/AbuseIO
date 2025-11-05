<?php

use AbuseIO\Http\Controllers\UsersController;

// Model binding is centralized in RouteServiceProvider

Route::group(
    [
        'prefix' => 'users',
        'as'     => 'users.',
    ],
    function () {
        // Search users
        Route::get('search/{one?}/{two?}/{three?}', [UsersController::class, 'search'])
            ->middleware('permission:users_view')
            ->name('search');

        // Access to index list
        Route::get('', [UsersController::class, 'index'])
            ->middleware('permission:users_view')
            ->name('index');

        // Access to show object
        Route::get('{users}', [UsersController::class, 'show'])
            ->middleware('permission:users_view')
            ->name('show');

        // Access to export object
        Route::get('export/{format}', [UsersController::class, 'export'])
            ->middleware('permission:users_export')
            ->name('export');

        // Access to create object
        Route::get('create', [UsersController::class, 'create'])
            ->middleware('permission:users_create')
            ->name('create');
        Route::post('', [UsersController::class, 'store'])
            ->middleware('permission:users_create')
            ->name('store');

        // Access to disable object
        Route::get('{users}/disable', [UsersController::class, 'disable'])
            ->middleware('permission:users_disable')
            ->name('disable');

        // Access to enable object
        Route::get('{users}/enable', [UsersController::class, 'enable'])
            ->middleware('permission:users_enable')
            ->name('enable');

        // Access to edit object
        Route::get('{users}/edit', [UsersController::class, 'edit'])
            ->middleware('permission:users_edit')
            ->name('edit');
        Route::match(['put', 'patch'], '{users}', [UsersController::class, 'update'])
            ->middleware('permission:users_edit')
            ->name('update');

        // Access to delete object
        Route::delete('/{users}', [UsersController::class, 'destroy'])
            ->middleware('permission:users_delete')
            ->name('destroy');
    }
);
