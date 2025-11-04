<?php

use AbuseIO\Http\Controllers\NotesController;

Route::group(
    [
        'prefix' => 'notes',
        'as'     => 'notes.',
    ],
    function () {
        // Access to index list
        Route::get('', [NotesController::class, 'index'])
            ->middleware('permission:notes_view')
            ->name('index');

        // Access to show object
        Route::get('{notes}', [NotesController::class, 'show'])
            ->middleware('permission:notes_view')
            ->name('show');

        // Access to create object
        Route::get('create', [NotesController::class, 'create'])
            ->middleware('permission:notes_create')
            ->name('create');
        Route::post('', [NotesController::class, 'store'])
            ->middleware(['permission:notes_create', 'appendnotesubmitter'])
            ->name('store');

        // Access to edit object
        Route::get('{notes}/edit', [NotesController::class, 'edit'])
            ->middleware('permission:notes_edit')
            ->name('edit');
        Route::match(['put', 'patch'], '{notes}', [NotesController::class, 'update'])
            ->middleware(['permission:notes_edit', 'appendnotesubmitter'])
            ->name('update');

        // Access to delete object
        Route::delete('/{notes}', [NotesController::class, 'destroy'])
            ->middleware('permission:notes_delete')
            ->name('destroy');
    }
);
