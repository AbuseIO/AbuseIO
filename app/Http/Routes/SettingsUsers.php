<?php

Route::model('users', 'AbuseIO\Models\User', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException('User Not Found.');
});

Route::resource('users', 'UsersController');

Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    // Search users
    route::get(
        '',
        [
            'middleware' => 'permission:accounts_view',
            'as'         => 'index',
            'uses'       => 'UsersController@search',
        ]
    );

    // Access to index list
    route::get(
        '',
        [
            'middleware' => 'permission:users_view',
            'as'         => 'index',
            'uses'       => 'UsersController@index',
        ]
    );

    // Access to export object
    route::get(
        'export/{format}',
        [
            'middleware' => 'permission:users_export',
            'as'         => 'export',
            'uses'       => 'UsersController@export',
        ]
    );

    // Access to create object
    route::get(
        'create',
        [
            'middleware' => 'permission:users_create',
            'as'         => 'create',
            'uses'       => 'UsersController@create',
        ]
    );
    route::post(
        '',
        [
            'middleware' => 'permission:users_create',
            'as'         => 'store',
            'uses'       => 'UsersController@store',
        ]
    );

    // Access to edit object
    route::get(
        '{users}/edit',
        [
            'middleware' => 'permission:users_edit',
            'as'         => 'edit',
            'uses'       => 'UsersController@edit',
        ]
    );
    // Access to delete object
    route::delete(
        '/{users}',
        [
            'middleware' => 'permission:users_delete',
            'as'         => 'destroy',
            'uses'       => 'UsersController@destroy',
        ]
    );

    /*
     *
     * These are converted to ajax calls
     *
     */
    // Get User
    Route::get(
        '{users}',
        [
            'middleware' => 'permission:users_view',
            'as'         => 'get',
            'uses'       => 'UsersController@get',
        ]
    );
    // Update User
    route::patch(
        '{users}',
        [
            'middleware' => 'permission:users_edit',
            'as'         => 'update',
            'uses'       => 'UsersController@update',
        ]
    );

    // Disable User
    route::get(
        '{users}/disable',
        [
            'middleware' => 'permission:users_disable',
            'as'         => 'disable',
            'uses'       => 'UsersController@disable',
        ]
    );

    // Enable User
    route::get(
        '{users}/enable',
        [
            'middleware' => 'permission:users_enable',
            'as'         => 'enable',
            'uses'       => 'UsersController@enable',
        ]
    );
});
