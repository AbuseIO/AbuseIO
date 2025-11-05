<?php

use Illuminate\Support\Facades\Route;

// Guard all API routes with API enabled and token checks, under v1
// Public version info endpoint (no auth), used by tests
Route::get('getversioninfo', function () {
    return response()->json(['version' => 'v1']);
});

Route::prefix('v1')->middleware(['api.enabled', 'api.token'])->group(function () {
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