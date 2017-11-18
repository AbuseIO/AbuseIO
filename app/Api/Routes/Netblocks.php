<?php

Route::group(
    [
        'prefix'     => 'netblocks',
        'as'         => 'netblocks.',
        'middleware' => ['apiaccountavailable', 'apisystemaccount'],
    ],
    function () {
        Route::get(
            '',
            [
                'as'   => 'index',
                'uses' => 'NetblocksController@apiIndex',
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
