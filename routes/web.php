<?php

use AbuseIO\Http\Controllers\AshController;
use AbuseIO\Http\Controllers\Auth\LoginController;
use AbuseIO\Http\Controllers\BrandsController;
use AbuseIO\Http\Controllers\HomeController;
use AbuseIO\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

// Migrate legacy app/Http/routes.php content into modern routes/web.php
// Keep existing namespaces by importing controllers via FQCN or relying on RouteServiceProvider namespace.

// Root redirects to admin home
Route::get('/', function () {
    return redirect('/admin/home');
});

// Authentication routes
Route::get('auth/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('auth/login', [LoginController::class, 'login']);
Route::get('auth/logout', [LoginController::class, 'logout'])->name('logout');

// Include modular route files under unified admin group
Route::prefix('admin')->middleware(['web', 'auth', 'permission:login_portal'])->as('admin.')->group(function () {
    // Redirect admin root to admin home
    Route::get('/', function () {
        return redirect('/admin/home');
    });

    // Admin home
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Admin version check endpoint
    Route::get('version', [HomeController::class, 'version'])->name('version');

    // Locale switch inside admin
    Route::get('locale/{locale}', [LocaleController::class, 'setLocale'])->name('locale');

    foreach ([
        'Analytics',
        'Contacts',
        'Domains',
        'Evidence',
        'Gdpr',
        'Incidents',
        'Netblocks',
        'Notes',
        'Profile',
        'SettingsAccounts',
        'SettingsBrands',
        'SettingsUsers',
        'Tickets',
    ] as $module) {
        $path = app_path("Http/Routes/{$module}.php");
        if (file_exists($path)) {
            require $path;
        }
    }
});

// Public ASH routes
Route::prefix('ash')->group(function () {
    Route::get('collect/{ticketID}/{token}', [AshController::class, 'index'])
        ->middleware(['ash.token'])
        ->name('ash.show');

    Route::post('collect/{ticketID}/{token}', [AshController::class, 'addNote'])
        ->middleware(['ash.token'])
        ->name('ash.addnote');

    Route::get('locale/{locale}', [LocaleController::class, 'setLocale'])
        ->name('ash.locale');

    Route::get('logo/{id}', [BrandsController::class, 'logo'])
        ->name('ash.logo');
});
