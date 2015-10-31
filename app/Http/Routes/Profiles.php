<?php
Route::resource('profile', 'ProfilesController');

Route::group(
    [
        'prefix' => 'profiles',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:profiles_view',
                'as' => 'admin.profiles.index',
                'uses' => 'ProfilesController@index'
            ]
        );

        // Access to show object
        route::get(
            '{profiles}',
            [
                'middleware' => 'permission:profiles_view',
                'as' => 'admin.profiles.show',
                'uses' => 'ProfilesController@show'
            ]
        );

        // Access to export object
        route::get(
            'export',
            [
                'middleware' => 'permission:profiles_export',
                'as' => 'admin.profiles.export',
                'uses' => 'ProfilesController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:profiles_create',
                'as' => 'admin.profiles.create',
                'uses' => 'ProfilesController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:profiles_create',
                'as' => 'admin.profiles.store',
                'uses' => 'ProfilesController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{profiles}/edit',
            [
                'middleware' => 'permission:profiles_edit',
                'as' => 'admin.profiles.edit',
                'uses' => 'ProfilesController@edit'
            ]
        );
        route::patch(
            '{profiles}',
            [
                'middleware' => 'permission:profiles_edit',
                'as' => '',
                'uses' => 'ProfilesController@update'
            ]
        );
        route::put(
            '{profiles}',
            [
                'middleware' => 'permission:profiles_edit',
                'as' => 'admin.profiles.update',
                'uses' => 'ProfilesController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{profiles}',
            [
                'middleware' => 'permission:profiles_delete',
                'as' => 'admin.profiles.destroy',
                'uses' => 'ProfilesController@destroy'
            ]
        );

    }
);
