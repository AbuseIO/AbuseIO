<?php
Route::group(
    [
        'prefix' => 'analytics',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:admin_analytics_view',
                'as' => 'admin.analytics',
                'uses' => 'AnalyticsController@index'
            ]
        );
    }
);