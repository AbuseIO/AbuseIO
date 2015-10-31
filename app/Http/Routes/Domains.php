<?php
Route::resource('domains', 'DomainsController');
Route::model('domains', 'AbuseIO\Models\Domain');

Route::group(
    [
        'prefix' => 'domains',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:admin_domains_view',
                'as' => 'admin.domains.index',
                'uses' => 'DomainsController@index'
            ]
        );

        // Access to show object
        route::get(
            '{domains}',
            [
                'middleware' => 'permission:admin_domains_view',
                'as' => 'admin.domains.show',
                'uses' => 'DomainsController@show'
            ]
        );

        // Access to export object
        route::get(
            'export',
            [
                'middleware' => 'permission:admin_domains_export2',
                'as' => 'admin.domains.export',
                'uses' => 'DomainsController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:admin_domains_create',
                'as' => 'admin.domains.create',
                'uses' => 'DomainsController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:admin_domains_create',
                'as' => 'admin.domains.store',
                'uses' => 'DomainsController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{domains}/edit',
            [
                'middleware' => 'permission:admin_domains_edit',
                'as' => 'admin.domains.edit',
                'uses' => 'DomainsController@edit'
            ]
        );
        route::patch(
            '{domains}',
            [
                'middleware' => 'permission:admin_domains_edit',
                'as' => '',
                'uses' => 'DomainsController@update'
            ]
        );
        route::put(
            '{domains}',
            [
                'middleware' => 'permission:admin_domains_edit',
                'as' => 'admin.domains.update',
                'uses' => 'DomainsController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{domains}',
            [
                'middleware' => 'permission:admin_domains_delete',
                'as' => 'admin.domains.destroy',
                'uses' => 'DomainsController@destroy'
            ]
        );

    }
);
