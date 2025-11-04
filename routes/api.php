<?php

use Illuminate\Support\Facades\Route;

// Guard all API routes with API enabled and token checks
// Use legacy aliases for broad compatibility across Laravel versions
Route::middleware(['apienabled', 'checkapitoken'])->group(function () {
    // Include modular API route files from app/Api/Routes
    foreach ([
        'Accounts',
        'Brands',
        'Contacts',
        'Domains',
        'Gdpr',
        'Incidents',
        'Netblocks',
        'Notes',
        'Tickets',
        'Users',
    ] as $module) {
        $path = app_path("Api/Routes/{$module}.php");
        if (file_exists($path)) {
            require $path;
        }
    }
});