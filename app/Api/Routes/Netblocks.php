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

        Route::post(
            '',
            [
                'as'   => 'store',
                'uses' => 'NetblocksController@apiStore',
            ]
        );

        Route::put(
            '{netblocks}',
            [
                'as'   => 'update',
                'uses' => 'NetblocksController@apiUpdate',
            ]
        );
    }
);
