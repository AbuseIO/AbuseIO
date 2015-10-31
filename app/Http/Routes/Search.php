<?php
Route::group(
    [
        'prefix' => 'search',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:search_view',
                'as' => 'admin.search',
                'uses' => 'SearchController@index'
            ]
        );
    }
);