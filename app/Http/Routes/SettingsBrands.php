<?php

Route::group(
    [
        'prefix' => 'brands',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:admin_brands_view',
                'as' => 'admin.brands.index',
                'uses' => 'BrandsController@index'
            ]
        );

        // Access to show object
        route::get(
            '{brands}',
            [
                'middleware' => 'permission:admin_brands_view',
                'as' => 'admin.brands.show',
                'uses' => 'BrandsController@show'
            ]
        );

        // Access to export object
        route::get(
            'export',
            [
                'middleware' => 'permission:admin_brands_export2',
                'as' => 'admin.brands.export',
                'uses' => 'BrandsController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:admin_brands_create',
                'as' => 'admin.brands.create',
                'uses' => 'BrandsController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:admin_brands_create',
                'as' => 'admin.brands.store',
                'uses' => 'BrandsController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{brands}/edit',
            [
                'middleware' => 'permission:admin_brands_edit',
                'as' => 'admin.brands.edit',
                'uses' => 'BrandsController@edit'
            ]
        );
        route::patch(
            '{brands}',
            [
                'middleware' => 'permission:admin_brands_edit',
                'as' => '',
                'uses' => 'BrandsController@update'
            ]
        );
        route::put(
            '{brands}',
            [
                'middleware' => 'permission:admin_brands_edit',
                'as' => 'admin.brands.update',
                'uses' => 'BrandsController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{brands}',
            [
                'middleware' => 'permission:admin_brands_delete',
                'as' => 'admin.brands.destroy',
                'uses' => 'BrandsController@destroy'
            ]
        );

    }
);
