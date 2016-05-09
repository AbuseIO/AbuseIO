<?php

Route::group(
    [
        'prefix' => 'netblocks',
        'as'     => 'netblocks.',
    ],
    function () {
        Route::get(
            '',
            [
                'as'   => 'index',
                'uses' => 'NetBlocksController@apiIndex',
            ]
        );

        Route::get(
            '{netblocks}',
            [
                'as'   => 'show',
                'uses' => 'NetblocksController@apiShow',
            ]
        );

        Route::delete(
            '{netblocks}',
            [
                'as'   => 'delete',
                'uses' => 'NetblocksController@apiDestroy',
            ]
        );
//
//        Route::post(
//            '',
//            [
//                'as'   => 'store',
//                'uses' => 'BrandsController@apiStore',
//            ]
//        );
//
//        Route::put(
//            '{brands}',
//            [
//                'as'   => 'update',
//                'uses' => 'BrandsController@apiUpdate',
//            ]
//        );
    }
);
