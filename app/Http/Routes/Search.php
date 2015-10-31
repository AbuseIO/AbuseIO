<?php
Route::group(
    [
        'prefix' => 'search',
        'as' => 'search.',
    ],
    function () {
        // Access to index list
        route::get(
            '',
            [
                'middleware' => 'permission:search_view',
                'as' => 'view',
                'uses' => 'SearchController@index'
            ]
        );
    }
);
