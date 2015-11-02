<?php
Route::resource('profiles', 'ProfilesController');

Route::group(
    [
        'prefix' => 'profiles',
        'as' => 'profiles.',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:profiles_view',
                'as' => 'index',
                'uses' => 'ProfilesController@index'
            ]
        );

        // Access to show object
        route::get(
            '{profiles}',
            [
                'middleware' => 'permission:profiles_view',
                'as' => 'show',
                'uses' => 'ProfilesController@show'
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:profiles_export',
                'as' => 'export',
                'uses' => 'ProfilesController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:profiles_create',
                'as' => 'create',
                'uses' => 'ProfilesController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:profiles_create',
                'as' => 'store',
                'uses' => 'ProfilesController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{profiles}/edit',
            [
                'middleware' => 'permission:profiles_edit',
                'as' => 'edit',
                'uses' => 'ProfilesController@edit'
            ]
        );
        route::patch(
            '{profiles}',
            [
                'middleware' => 'permission:profiles_edit',
                'as' => 'update',
                'uses' => 'ProfilesController@update'
            ]
        );
        route::put(
            '{profiles}',
            [
                'middleware' => 'permission:profiles_edit',
                'as' => 'update',
                'uses' => 'ProfilesController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{profiles}',
            [
                'middleware' => 'permission:profiles_delete',
                'as' => 'destroy',
                'uses' => 'ProfilesController@destroy'
            ]
        );

    }
);
