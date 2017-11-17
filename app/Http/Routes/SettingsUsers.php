<?php

Route::model('users', 'AbuseIO\Models\User', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException('User Not Found.');
});

Route::resource('users', 'UsersController');

Route::group(['prefix' => 'users', 'as' => 'users.'], function () {

    // Index User
    route::get(
        '',
        [
            'middleware' => 'permission:users_view',
            'as'         => 'index',
            'uses'       => 'UsersController@index',
        ]
    );

    // Get User
    Route::get(
        '{users}',
        [
            'middleware' => 'permission:users_view',
            'as'         => 'get',
            'uses'       => 'UsersController@get',
        ]
    );

    // Create User
    route::post(
        '',
        [
            'middleware' => 'permission:users_create',
            'as'         => 'store',
            'uses'       => 'UsersController@store',
        ]
    );

    // Update User
    Route::patch(
        '{users}',
        [
            'middleware' => 'permission:users_edit',
            'as'         => 'update',
            'uses'       => 'UsersController@update',
        ]
    );

    // Disable User
    Route::patch(
        '{users}/disable',
        [
            'middleware' => 'permission:users_disable',
            'as'         => 'disable',
            'uses'       => 'UsersController@disable',
        ]
    );

    // Enable User
    Route::patch(
        '{users}/enable',
        [
            'middleware' => 'permission:users_enable',
            'as'         => 'enable',
            'uses'       => 'UsersController@enable',
        ]
    );

    // Delete User
    Route::delete(
        '{users}',
        [
            'middleware' => 'permission:users_delete',
            'as'         => 'destroy',
            'uses'       => 'UsersController@destroy',
        ]
    );
});
