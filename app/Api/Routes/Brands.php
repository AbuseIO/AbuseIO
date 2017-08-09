<?php

Route::group(
    [
        'prefix'     => 'brands',
        'as'         => 'brands.',
        'middleware' => ['apiaccountavailable'],
    ],
    function () {
        Route::get(
            '',
            [
                'as'   => 'index',
                'uses' => 'BrandsController@apiIndex',
            ]
        );

        Route::get(
            '{brands}',
            [
                'as'   => 'show',
                'uses' => 'BrandsController@apiShow',
            ]
        );

        Route::delete(
            '{id}',
            [
                'as'   => 'delete',
                'uses' => 'BrandsController@apiDestroy',
            ]
        );

        Route::post(
            '',
            [
                'as'   => 'store',
                'uses' => 'BrandsController@apiStore',
            ]
        );

        Route::put(
            '{brands}',
            [
                'as'   => 'update',
                'uses' => 'BrandsController@apiUpdate',
            ]
        );
    }
);
