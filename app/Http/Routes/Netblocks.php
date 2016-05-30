<?php

Route::resource('netblocks', 'NetblocksController');

Route::model('netblocks', 'AbuseIO\Models\Netblock', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
});

Route::group(
    [
        'prefix' => 'netblocks',
        'as'     => 'netblocks.',
    ],
    function () {
        // Search netblock
        Route::get(
            'search/{one?}/{two?}/{three?}',
            [
                'middleware' => 'permission:netblocks_view',
                'as'         => 'search',
                'uses'       => 'NetblocksController@search',
            ]
        );

        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:netblocks_view',
                'as'         => 'index',
                'uses'       => 'NetblocksController@index',
            ]
        );

        // Access to show object
        route::get(
            '{netblocks}',
            [
                'middleware' => 'permission:netblocks_view',
                'as'         => 'show',
                'uses'       => 'NetblocksController@show',
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:netblocks_export',
                'as'         => 'export',
                'uses'       => 'NetblocksController@export',
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:netblocks_create',
                'as'         => 'create',
                'uses'       => 'NetblocksController@create',
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:netblocks_create',
                'as'         => 'store',
                'uses'       => 'NetblocksController@store',
            ]
        );

        // Access to edit object
        route::get(
            '{netblocks}/edit',
            [
                'middleware' => 'permission:netblocks_edit',
                'as'         => 'edit',
                'uses'       => 'NetblocksController@edit',
            ]
        );
        route::patch(
            '{netblocks}',
            [
                'middleware' => 'permission:netblocks_edit',
                'as'         => 'update',
                'uses'       => 'NetblocksController@update',
            ]
        );
        route::put(
            '{netblocks}',
            [
                'middleware' => 'permission:netblocks_edit',
                'as'         => 'update',
                'uses'       => 'NetblocksController@update',
            ]
        );

        // Access to delete object
        route::delete(
            '/{netblocks}',
            [
                'middleware' => 'permission:netblocks_delete',
                'as'         => 'destroy',
                'uses'       => 'NetblocksController@destroy',
            ]
        );
    }
);
