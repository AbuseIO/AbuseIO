<?php
Route::resource('netblocks', 'NetblocksController');
Route::model('netblocks', 'AbuseIO\Models\Netblock');

Route::group(
    [
        'prefix' => 'netblocks',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:netblocks_view',
                'as' => 'admin.netblocks.index',
                'uses' => 'NetblocksController@index'
            ]
        );

        // Access to show object
        route::get(
            '{netblocks}',
            [
                'middleware' => 'permission:netblocks_view',
                'as' => 'admin.netblocks.show',
                'uses' => 'NetblocksController@show'
            ]
        );

        // Access to export object
        route::get(
            'export',
            [
                'middleware' => 'permission:netblocks_export',
                'as' => 'admin.netblocks.export',
                'uses' => 'NetblocksController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:netblocks_create',
                'as' => 'admin.netblocks.create',
                'uses' => 'NetblocksController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:netblocks_create',
                'as' => 'admin.netblocks.store',
                'uses' => 'NetblocksController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{netblocks}/edit',
            [
                'middleware' => 'permission:netblocks_edit',
                'as' => 'admin.netblocks.edit',
                'uses' => 'NetblocksController@edit'
            ]
        );
        route::patch(
            '{netblocks}',
            [
                'middleware' => 'permission:netblocks_edit',
                'as' => '',
                'uses' => 'NetblocksController@update'
            ]
        );
        route::put(
            '{netblocks}',
            [
                'middleware' => 'permission:netblocks_edit',
                'as' => 'admin.netblocks.update',
                'uses' => 'NetblocksController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{netblocks}',
            [
                'middleware' => 'permission:netblocks_delete',
                'as' => 'admin.netblocks.destroy',
                'uses' => 'NetblocksController@destroy'
            ]
        );

    }
);
