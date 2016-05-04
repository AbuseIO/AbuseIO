<?php

Route::group(
    [
        'prefix' => 'brands',
        'as' => 'brands.',
    ],
    function () {
        Route::get(
            '',
            [
                'as' => 'index',
                'uses' => 'BrandsController@apiIndex',
            ]
        );

        Route::get(
            '{id}',
            [
                'as' => 'index',
                'uses' => 'BrandsController@apiShow',
            ]
        );

        Route::delete(
            '{id}',
            [
                'as' => 'delete',
                'uses' => "BrandsController@apiDestroy",
            ]
        );
    }
);
