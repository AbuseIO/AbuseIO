<?php

use AbuseIO\Http\Controllers\ContactsController;

Route::group(
    [
        'prefix' => 'contacts',
        'as'     => 'contacts.',
    ],
    function () {
        // Search contacts
        Route::get('search/{one?}/{two?}/{three?}/{four?}/{five?}', [ContactsController::class, 'search'])
            ->middleware('permission:contacts_view')
            ->name('search');

        // Access to index list
        Route::get('', [ContactsController::class, 'index'])
            ->middleware('permission:contacts_view')
            ->name('index');

        // Access to show object (id must be numeric)
        Route::get('{contacts}', [ContactsController::class, 'show'])
            ->middleware('permission:contacts_view')
            ->where('contacts', '[0-9]+')
            ->name('show');

        // Access to export object
        Route::get('export/{format}', [ContactsController::class, 'export'])
            ->middleware('permission:contacts_export')
            ->name('export');

        // Access to create object
        Route::get('create', [ContactsController::class, 'create'])
            ->middleware('permission:contacts_create')
            ->name('create');
        Route::post('', [ContactsController::class, 'store'])
            ->middleware('permission:contacts_create')
            ->name('store');

        // Access to edit object (id must be numeric)
        Route::get('{contacts}/edit', [ContactsController::class, 'edit'])
            ->middleware('permission:contacts_edit')
            ->where('contacts', '[0-9]+')
            ->name('edit');
        Route::match(['put', 'patch'], '{contacts}', [ContactsController::class, 'update'])
            ->middleware('permission:contacts_edit')
            ->where('contacts', '[0-9]+')
            ->name('update');

        // Access to delete object (id must be numeric)
        Route::delete('{contacts}', [ContactsController::class, 'destroy'])
            ->middleware('permission:contacts_delete')
            ->where('contacts', '[0-9]+')
            ->name('destroy');
    }
);
