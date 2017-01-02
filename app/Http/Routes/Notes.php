<?php

Route::resource('notes', 'NotesController');

Route::model('notes', 'AbuseIO\Models\Note', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
});

Route::group(
    [
        'prefix' => 'notes',
        'as'     => 'notes.',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:notes_view',
                'as'         => 'index',
                'uses'       => 'NotesController@index',
            ]
        );

        // Access to show object
        route::get(
            '{notes}',
            [
                'middleware' => 'permission:notes_view',
                'as'         => 'show',
                'uses'       => 'NotesController@show',
            ]
        );

        // Access to create object
        route::get(
            'create',
            [
                'middleware' => 'permission:notes_create',
                'as'         => 'create',
                'uses'       => 'NotesController@create',
            ]
        );
        route::post(
            '',
            [
                'middleware' => ['permission:notes_create', 'appendnotesubmitter'],
                'as'         => 'store',
                'uses'       => 'NotesController@store',
            ]
        );

        // Access to edit object
        route::get(
            '{notes}/edit',
            [
                'middleware' => 'permission:notes_edit',
                'as'         => 'edit',
                'uses'       => 'NotesController@edit',
            ]
        );
        route::patch(
            '{notes}',
            [
                'middleware' => ['permission:notes_edit', 'appendnotesubmitter'],
                'as'         => 'update',
                'uses'       => 'NotesController@update',
            ]
        );
        route::put(
            '{notes}',
            [
                'middleware' => ['permission:notes_edit', 'appendnotesubmitter'],
                'as'         => 'update',
                'uses'       => 'NotesController@update',
            ]
        );

        // Access to delete object
        route::delete(
            '/{notes}',
            [
                'middleware' => 'permission:notes_delete',
                'as'         => 'destroy',
                'uses'       => 'NotesController@destroy',
            ]
        );
    }
);
