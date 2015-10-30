<?php

Route::group(
    [
        'prefix' => 'users',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'acl:admin_users_view',
                'as' => 'admin.users.index',
                'uses' => 'UsersController@index'
            ]
        );

        // Access to show object
        route::get(
            '{users}',
            [
                'middleware' => 'acl:admin_users_view',
                'as' => 'admin.users.show',
                'uses' => 'UsersController@show'
            ]
        );

        // Access to export object
        route::get(
            'export',
            [
                'middleware' => 'acl:admin_users_export2',
                'as' => 'admin.users.export',
                'uses' => 'UsersController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'acl:admin_users_create',
                'as' => 'admin.users.create',
                'uses' => 'UsersController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'acl:admin_users_create',
                'as' => 'admin.users.store',
                'uses' => 'UsersController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{users}/edit',
            [
                'middleware' => 'acl:admin_users_edit',
                'as' => 'admin.users.edit',
                'uses' => 'UsersController@edit'
            ]
        );
        route::patch(
            '{users}',
            [
                'middleware' => 'acl:admin_users_edit',
                'as' => '',
                'uses' => 'UsersController@update'
            ]
        );
        route::put(
            '{users}',
            [
                'middleware' => 'acl:admin_users_edit',
                'as' => 'admin.users.update',
                'uses' => 'UsersController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{users}',
            [
                'middleware' => 'acl:admin_users_delete',
                'as' => 'admin.users.destroy',
                'uses' => 'UsersController@destroy'
            ]
        );

    }
);
