<?php
use AbuseIO\Http\Controllers\NotesController;

Route::group(
    [
        'prefix'     => 'notes',
        'as'         => 'notes',
        'middleware' => ['api.account', 'api.system'],
    ],
    function () {
        // Access to index list
        Route::get('', [NotesController::class, 'apiIndex'])->name('index');

        // Access to show object
        Route::get('{notes}', [NotesController::class, 'apiShow'])->name('show');
        //
        //        Route::delete(
        //            '{notes}',
        //            [
        //                'as'   => 'delete',
        //                'uses' => 'NotesController@apiDestroy',
        //            ]
        //        );
        //
        Route::post('', [NotesController::class, 'apiStore'])->name('store');
        //
//        Route::put(
//            '{notes}',
//            [
//                'as'   => 'update',
//                'uses' => 'NotesController@apiUpdate',
//            ]
//        );
    }
);
