<?php

Route::model('incidents', 'AbuseIO\Models\Incident', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
});

Route::resource('incidents', 'IncidentsController');

Route::group(
    [
        'prefix' => 'incidents',
        'as'     => 'incidents.',
    ],
    function () {
        /*
        | Create incident
        */
        Route::get(
            'create',
            [
                'middleware' => 'permission:incidents_create',
                'as'         => 'create',
                'uses'       => 'IncidentsController@create',
            ]
        );
        Route::post(
            '',
            [
                'middleware' => 'permission:incidents_create',
                'as'         => 'store',
                'uses'       => 'IncidentsController@store',
            ]
        );
    }
);
