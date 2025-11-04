<?php
use AbuseIO\Http\Controllers\BrandsController;

Route::group(
    [
        'prefix'     => 'brands',
        'as'         => 'brands.',
        'middleware' => ['apiaccountavailable', 'apisystemaccount'],
    ],
    function () {
        Route::get('', [BrandsController::class, 'apiIndex'])->name('index');

        Route::get('{brands}', [BrandsController::class, 'apiShow'])->name('show');

        Route::delete('{id}', [BrandsController::class, 'apiDestroy'])->name('delete');

        /*
         * not correct implemented
        Route::post(
            '',
            [
                'as'   => 'store',
                'uses' => 'BrandsController@apiStore',
            ]
        );
        **/

        Route::put('{brands}', [BrandsController::class, 'apiUpdate'])->name('update');
    }
);
