<?php
Route::resource('contacts', 'ContactsController');
Route::model('contacts', 'AbuseIO\Models\Contact');

Route::group(
    [
        'prefix' => 'contacts',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:admin_contacts_view',
                'as' => 'admin.contacts.index',
                'uses' => 'ContactsController@index'
            ]
        );

        // Access to show object
        route::get(
            '{contacts}',
            [
                'middleware' => 'permission:admin_contacts_view',
                'as' => 'admin.contacts.show',
                'uses' => 'ContactsController@show'
            ]
        );

        // Access to export object
        route::get(
            'export',
            [
                'middleware' => 'permission:admin_contacts_export2',
                'as' => 'admin.contacts.export',
                'uses' => 'ContactsController@export'
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:admin_contacts_create',
                'as' => 'admin.contacts.create',
                'uses' => 'ContactsController@create'
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:admin_contacts_create',
                'as' => 'admin.contacts.store',
                'uses' => 'ContactsController@store'
            ]
        );

        // Access to edit object
        route::get(
            '{contacts}/edit',
            [
                'middleware' => 'permission:admin_contacts_edit',
                'as' => 'admin.contacts.edit',
                'uses' => 'ContactsController@edit'
            ]
        );
        route::patch(
            '{contacts}',
            [
                'middleware' => 'permission:admin_contacts_edit',
                'as' => '',
                'uses' => 'ContactsController@update'
            ]
        );
        route::put(
            '{contacts}',
            [
                'middleware' => 'permission:admin_contacts_edit',
                'as' => 'admin.contacts.update',
                'uses' => 'ContactsController@update'
            ]
        );

        // Access to delete object
        route::delete(
            '/{contacts}',
            [
                'middleware' => 'permission:admin_contacts_delete',
                'as' => 'admin.contacts.destroy',
                'uses' => 'ContactsController@destroy'
            ]
        );

    }
);
