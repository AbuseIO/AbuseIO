<?php

Route::resource('gdpr', 'GdprController');

Route::group(
    [
        'prefix' => 'gdpr',
        'as'     => 'gdpr.',
    ],
    function () {
        // Access to edit object
        route::post(
            '{contact}',
            [
                'middleware' => 'permission:contacts_edit',
                'as'         => 'anonimize',
                'uses'       => 'GdprController@anonimize',
            ]
        );
    }
);
