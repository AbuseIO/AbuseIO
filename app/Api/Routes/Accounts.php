<?php

Route::group(
    [
        'prefix'     => 'accounts',
        'as'         => 'accounts.',
    ],
    function () {
        Route::get(
            '',
            function () {
                return 'hello world';
            }
        );

    }
);
