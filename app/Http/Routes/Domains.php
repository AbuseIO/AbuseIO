<?php
Route::resource('domains', 'DomainsController');
Route::model('domains', 'AbuseIO\Models\Domain');

Route::group(
    [
        'prefix' => 'domains',
        'as' => 'domains.',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:domains_view',
                'as' => 'index',
                'uses' => 'DomainsController@index'
            ]
        );

        // Access to show object
        route::get(
            '{domains}',
            [
                'middleware' => 'permission:domains_view',
                'as' => 'show',
                'uses' => 'DomainsController@show'
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:domains_export',
                'as' => 'export',
                'uses' => 'DomainsController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:domains_create',
                'as' => 'create',
                'uses' => 'DomainsController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:domains_create',
                'as' => 'store',
                'uses' => 'DomainsController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{domains}/edit',
            [
                'middleware' => 'permission:domains_edit',
                'as' => 'edit',
                'uses' => 'DomainsController@edit'
            ]
        );
        route::patch(
            '{domains}',
            [
                'middleware' => 'permission:domains_edit',
                'as' => 'update',
                'uses' => 'DomainsController@update'
            ]
        );
        route::put(
            '{domains}',
            [
                'middleware' => 'permission:domains_edit',
                'as' => 'update',
                'uses' => 'DomainsController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{domains}',
            [
                'middleware' => 'permission:domains_delete',
                'as' => 'destroy',
                'uses' => 'DomainsController@destroy'
            ]
        );

    }
);
