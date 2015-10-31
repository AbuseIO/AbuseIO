<?php
Route::model('brands', 'AbuseIO\Models\Brand');
Route::resource('brands', 'BrandsController');

Route::group(
    [
        'prefix' => 'brands',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:brands_view',
                'as' => 'admin.brands.index',
                'uses' => 'BrandsController@index'
            ]
        );

        // Access to show object
        route::get(
            '{brands}',
            [
                'middleware' => 'permission:brands_view',
                'as' => 'admin.brands.show',
                'uses' => 'BrandsController@show'
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:brands_export',
                'as' => 'admin.brands.export',
                'uses' => 'BrandsController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:brands_create',
                'as' => 'admin.brands.create',
                'uses' => 'BrandsController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:brands_create',
                'as' => 'admin.brands.store',
                'uses' => 'BrandsController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{brands}/edit',
            [
                'middleware' => 'permission:brands_edit',
                'as' => 'admin.brands.edit',
                'uses' => 'BrandsController@edit'
            ]
        );
        route::patch(
            '{brands}',
            [
                'middleware' => 'permission:brands_edit',
                'as' => '',
                'uses' => 'BrandsController@update'
            ]
        );
        route::put(
            '{brands}',
            [
                'middleware' => 'permission:brands_edit',
                'as' => 'admin.brands.update',
                'uses' => 'BrandsController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{brands}',
            [
                'middleware' => 'permission:brands_delete',
                'as' => 'admin.brands.destroy',
                'uses' => 'BrandsController@destroy'
            ]
        );

    }
);
