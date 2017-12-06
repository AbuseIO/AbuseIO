<?php

Route::group(
    [
        'prefix'     => 'brands',
        'as'         => 'brands.',
        'middleware' => ['apiaccountavailable', 'apisystemaccount'],
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

        Route::put(
            '{brands}',
            [
                'as'   => 'update',
                'uses' => 'BrandsController@apiUpdate',
            ]
        );
    }
);
