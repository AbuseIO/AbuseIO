<?php

Route::group(
    [
        'prefix' => 'profiles',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'acl:admin_profiles_view',
                'as' => 'admin.profiles.index',
                'uses' => 'ProfilesController@index'
            ]
        );

        // Access to show object
        route::get(
            '{profiles}',
            [
                'middleware' => 'acl:admin_profiles_view',
                'as' => 'admin.profiles.show',
                'uses' => 'ProfilesController@show'
            ]
        );

        // Access to export object
        route::get(
            'export',
            [
                'middleware' => 'acl:admin_profiles_export2',
                'as' => 'admin.profiles.export',
                'uses' => 'ProfilesController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'acl:admin_profiles_create',
                'as' => 'admin.profiles.create',
                'uses' => 'ProfilesController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'acl:admin_profiles_create',
                'as' => 'admin.profiles.store',
                'uses' => 'ProfilesController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{profiles}/edit',
            [
                'middleware' => 'acl:admin_profiles_edit',
                'as' => 'admin.profiles.edit',
                'uses' => 'ProfilesController@edit'
            ]
        );
        route::patch(
            '{profiles}',
            [
                'middleware' => 'acl:admin_profiles_edit',
                'as' => '',
                'uses' => 'ProfilesController@update'
            ]
        );
        route::put(
            '{profiles}',
            [
                'middleware' => 'acl:admin_profiles_edit',
                'as' => 'admin.profiles.update',
                'uses' => 'ProfilesController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{profiles}',
            [
                'middleware' => 'acl:admin_profiles_delete',
                'as' => 'admin.profiles.destroy',
                'uses' => 'ProfilesController@destroy'
            ]
        );

    }
);
