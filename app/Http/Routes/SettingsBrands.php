<?php

Route::model('brands', 'AbuseIO\Models\Brand', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Brand Not Found.');
});
Route::resource('brands', 'BrandsController');

Route::group(
    [
        'prefix' => 'brands',
        'as'     => 'brands.',
    ],
    function () {
        // Search contacts
        Route::get(
            'search/{one?}/{two?}/{three?}/{four?}/{five?}',
            [
                'middleware' => 'permission:brands_view',
                'as'         => 'search',
                'uses'       => 'BrandsController@search',
            ]
        );

        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:brands_view',
                'as'         => 'index',
                'uses'       => 'BrandsController@index',
            ]
        );

        // Access to show object
        route::get(
            '{brands}',
            [
                'middleware' => 'permission:brands_view',
                'as'         => 'show',
                'uses'       => 'BrandsController@show',
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:brands_export',
                'as'         => 'export',
                'uses'       => 'BrandsController@export',
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:brands_create',
                'as'         => 'create',
                'uses'       => 'BrandsController@create',
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:brands_create',
                'as'         => 'store',
                'uses'       => 'BrandsController@store',
            ]
        );

        // Access to edit object
        route::get(
            '{brands}/edit',
            [
                'middleware' => 'permission:brands_edit',
                'as'         => 'edit',
                'uses'       => 'BrandsController@edit',
            ]
        );
        // Access to activate object
        route::get(
            '{brands}/activate',
            [
                'middleware' => 'permission:brands_edit',
                'as'         => 'activate',
                'uses'       => 'BrandsController@activate',
            ]
        );
        route::patch(
            '{brands}',
            [
                'middleware' => 'permission:brands_edit',
                'as'         => 'update',
                'uses'       => 'BrandsController@update',
            ]
        );
        route::put(
            '{brands}',
            [
                'middleware' => 'permission:brands_edit',
                'as'         => 'update',
                'uses'       => 'BrandsController@update',
            ]
        );

        // Access to delete object
        route::delete(
            '/{brands}',
            [
                'middleware' => 'permission:brands_delete',
                'as'         => 'destroy',
                'uses'       => 'BrandsController@destroy',
            ]
        );
    }
);
