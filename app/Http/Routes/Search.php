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
                'middleware' => 'acl:admin_search_view',
                'as' => 'admin.search',
                'uses' => 'SearchController@index'
            ]
        );
    }
);