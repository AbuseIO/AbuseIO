<?php

Route::group(
    [
        'prefix' => 'contacts',
        'as'     => 'contacts.',
    ],
    function () {
        Route::get(
            '',
            [
                'as'   => 'index',
                'uses' => 'ContactsController@apiIndex',
            ]
        );

        Route::get(
            '{contacts}',
            [
                'as'   => 'show',
                'uses' => 'ContactsController@apiShow',
            ]
        );

        Route::delete(
            '{contacts}',
            [
                'as'   => 'delete',
                'uses' => 'ContactsController@apiDestroy',
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
