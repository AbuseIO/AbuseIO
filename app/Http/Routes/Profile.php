<?php

Route::resource('profile', 'ProfileController');

Route::group(
    [
        'prefix' => 'profile',
        'as'     => 'profile.',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:profile_manage',
                'as'         => 'index',
                'uses'       => 'ProfileController@edit',
            ]
        );

        // Access to edit object
        route::patch(
            '{profile}',
            [
                'middleware' => 'permission:profile_manage',
                'as'         => 'update',
                'uses'       => 'ProfileController@update',
            ]
        );
        route::put(
            '{profile}',
            [
                'middleware' => 'permission:profile_manage',
                'as'         => 'update',
                'uses'       => 'ProfileController@update',
            ]
        );
    }
);
