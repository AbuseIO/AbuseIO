<?php

Route::group(
    [
        'prefix' => 'analytics',
        'as'     => 'analytics.',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:analytics_view',
                'as'         => 'view',
                'uses'       => 'AnalyticsController@index',
            ]
        );
        route::get(
            '',
            [
                'middleware' => 'permission:analytics_view',
                'as'         => 'index',
                'uses'       => 'AnalyticsController@index',
            ]
        );
        route::get(
            'graph',
            [
                'middleware' => 'permission:analytics_view',
                'as'         => 'view',
                'uses'       => 'AnalyticsController@show',
            ]
        );
    }
);
