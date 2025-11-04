<?php
use AbuseIO\Http\Controllers\TicketsController;

// Model binding is centralized in RouteServiceProvider

Route::group(
    [
        'prefix' => 'tickets',
        'as'     => 'tickets.',
    ],
    function () {
        /*
        | Ticket search
        */
        Route::get('search/{one?}/{two?}/{three?}/{four?}/{five?}', [TicketsController::class, 'search'])
            ->middleware('permission:tickets_view')
            ->name('search');

        /*
        | Index tickets
        */
        Route::get('', [TicketsController::class, 'index'])
            ->middleware('permission:tickets_view')
            ->name('index');

        /*
        | Show ticket
        */
        Route::get('{tickets}', [TicketsController::class, 'show'])
            ->middleware('permission:tickets_view')
            ->name('show');

        /*
        | Export tickets
        */
        Route::get('export/{format}', [TicketsController::class, 'export'])
            ->middleware('permission:tickets_export')
            ->name('export');

        /*
        | Create ticket
        */
        Route::get('create', [TicketsController::class, 'create'])
            ->middleware('permission:tickets_create')
            ->name('create');
        Route::post('', [TicketsController::class, 'store'])
            ->middleware('permission:tickets_create')
            ->name('store');

        /*
        | Edit ticket
        */
        Route::get('{tickets}/edit', [TicketsController::class, 'edit'])
            ->middleware('permission:tickets_edit')
            ->name('edit');
        Route::match(['put', 'patch'], '{tickets}', [TicketsController::class, 'update'])
            ->middleware('permission:tickets_edit')
            ->name('update');

        /*
        | Delete ticket
        */
        Route::delete('/{tickets}', [TicketsController::class, 'destroy'])
            ->middleware('permission:tickets_delete')
            ->name('destroy');

        /*
        | Contact information
        */
        Route::group(
            [
                'prefix' => '{tickets}/update',
            ],
            function () {
                Route::get('{who?}', [TicketsController::class, 'update'])
                    ->middleware('permission:tickets_edit')
                    ->name('contact_update');
            }
        );

        /*
        | Notifications
        */
        Route::group(
            [
                'prefix' => '{tickets}/notify',
            ],
            function () {
                Route::get('{who?}', [TicketsController::class, 'notify'])
                    ->middleware('permission:tickets_edit')
                    ->name('notify');
            }
        );

        /*
        | Edit ticket status
        */
        Route::group(
            [
                'prefix' => '{tickets}/status',
            ],
            function () {
                Route::get('{status}', [TicketsController::class, 'status'])
                    ->middleware('permission:tickets_edit')
                    ->name('status');
            }
        );
    }
);
