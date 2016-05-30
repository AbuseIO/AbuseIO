<?php

Route::resource('contacts', 'ContactsController');

Route::model('contacts', 'AbuseIO\Models\Contact', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
});

Route::group(
    [
        'prefix' => 'contacts',
        'as'     => 'contacts.',
    ],
    function () {
        // Search contacts
        Route::get(
            'search/{one?}/{two?}/{three?}/{four?}/{five?}',
            [
                'middleware' => 'permission:contacts_view',
                'as'         => 'search',
                'uses'       => 'ContactsController@search',
            ]
        );

        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:contacts_view',
                'as'         => 'index',
                'uses'       => 'ContactsController@index',
            ]
        );

        // Access to show object
        route::get(
            '{contacts}',
            [
                'middleware' => 'permission:contacts_view',
                'as'         => 'show',
                'uses'       => 'ContactsController@show',
            ]
        );

        // Access to export object
        route::get(
            'export/{format}',
            [
                'middleware' => 'permission:contacts_export',
                'as'         => 'export',
                'uses'       => 'ContactsController@export',
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:contacts_create',
                'as'         => 'create',
                'uses'       => 'ContactsController@create',
            ]
        );
        route::post(
            '',
            [
                'middleware' => 'permission:contacts_create',
                'as'         => 'store',
                'uses'       => 'ContactsController@store',
            ]
        );

        // Access to edit object
        route::get(
            '{contacts}/edit',
            [
                'middleware' => 'permission:contacts_edit',
                'as'         => 'edit',
                'uses'       => 'ContactsController@edit',
            ]
        );
        route::patch(
            '{contacts}',
            [
                'middleware' => 'permission:contacts_edit',
                'as'         => 'update',
                'uses'       => 'ContactsController@update',
            ]
        );
        route::put(
            '{contacts}',
            [
                'middleware' => 'permission:contacts_edit',
                'as'         => 'update',
                'uses'       => 'ContactsController@update',
            ]
        );

        // Access to delete object
        route::delete(
            '{contacts}',
            [
                'middleware' => 'permission:contacts_delete',
                'as'         => 'destroy',
                'uses'       => 'ContactsController@destroy',
            ]
        );
    }
);
