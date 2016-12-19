<?php

Route::resource('evidence', 'EvidenceController');
Route::model('evidence', 'AbuseIO\Models\Evidence', function () {
    throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
});

Route::group(
    [
        'prefix' => 'evidence',
        'as'     => 'evidence.',
    ],
    function () {
        /*
         * Index evidence
         */
        Route::get(
            '',
            [
                'middleware' => 'permission:evidence_view',
                'as'         => 'index',
                'uses'       => 'EvidenceController@index',
            ]
        );

        /*
        | Show evidence
        */
        Route::get(
            '{evidence}',
            [
                'middleware' => 'permission:evidence_view',
                'as'         => 'show',
                'uses'       => 'EvidenceController@show',
            ]
        );

        /*
        | Download evidence
        */
        Route::get(
            '{evidence}/download',
            [
                'middleware' => 'permission:evidence_view',
                'as'         => 'download',
                'uses'       => 'EvidenceController@download',
            ]
        );

        Route::get(
            '{evidence}/attachment/{file}',
            [
                'middleware' => 'permission:evidence_view',
                'as'         => 'attachment',
                'uses'       => 'EvidenceController@attachment',
            ]
        );
    }
);
