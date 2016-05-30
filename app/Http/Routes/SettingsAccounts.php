<?php

Route::model('accounts', 'AbuseIO\Models\Account', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
});

Route::resource('accounts', 'AccountsController');

Route::group(
    [
        'prefix' => 'accounts',
        'as'     => 'accounts.',
    ],
    function () {
        // Search contacts
        Route::get(
            'search/{one?}/{two?}/{three?}/{four?}/{five?}',
            [
                'middleware' => 'permission:accounts_view',
                'as'         => 'search',
                'uses'       => 'AccountsController@search',
            ]
        );

        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:accounts_view',
                'as'         => 'index',
                'uses'       => 'AccountsController@index',
            ]
        );

        // Access to show object
        route::get(
            '{accounts}',
            [
                'middleware' => 'permission:accounts_view',
                'as'         => 'show',
                'uses'       => 'AccountsController@show',
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:accounts_export',
                'as'         => 'export',
                'uses'       => 'AccountsController@export',
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:accounts_create',
                'as'         => 'create',
                'uses'       => 'AccountsController@create',
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:accounts_create',
                'as'         => 'store',
                'uses'       => 'AccountsController@store',
            ]
        );

        // Access to disable object
        route::get(
            '{accounts}/disable',
            [
                'middleware' => 'permission:accounts_disable',
                'as'         => 'disable',
                'uses'       => 'AccountsController@disable',
            ]
        );

        // Access to enable object
        route::get(
            '{accounts}/enable',
            [
                'middleware' => 'permission:accounts_enable',
                'as'         => 'enable',
                'uses'       => 'AccountsController@enable',
            ]
        );

        // Access to edit object
        route::get(
            '{accounts}/edit',
            [
                'middleware' => 'permission:accounts_edit',
                'as'         => 'edit',
                'uses'       => 'AccountsController@edit',
            ]
        );
        route::patch(
            '{accounts}',
            [
                'middleware' => 'permission:accounts_edit',
                'as'         => '',
                'uses'       => 'AccountsController@update',
            ]
        );
        route::put(
            '{accounts}',
            [
                'middleware' => 'permission:accounts_edit',
                'as'         => 'update',
                'uses'       => 'AccountsController@update',
            ]
        );

        // Access to delete object
        route::delete(
            '/{accounts}',
            [
                'middleware' => 'permission:accounts_delete',
                'as'         => 'destroy',
                'uses'       => 'AccountsController@destroy',
            ]
        );
    }
);
