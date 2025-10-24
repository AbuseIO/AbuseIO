<?php

use AbuseIO\Http\Controllers\AccountsController;
use AbuseIO\Http\Controllers\AshLinksController;
use AbuseIO\Http\Controllers\Auth\LoginController;
use AbuseIO\Http\Controllers\ContactsController;
use AbuseIO\Http\Controllers\DomainsController;
use AbuseIO\Http\Controllers\EventsController;
use AbuseIO\Http\Controllers\NetblocksController;
use AbuseIO\Http\Controllers\NotesController;
use AbuseIO\Http\Controllers\SearchController;
use AbuseIO\Http\Controllers\TicketsController;
use AbuseIO\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('auth/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('auth/login', [LoginController::class, 'login']);
Route::get('auth/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['auth', 'permission']], function () {
        Route::get('/home', function () {
            return view('home');
        })->name('home');

        /*
         * Tickets
         */
        Route::get('tickets/search', [TicketsController::class, 'search'])->name('tickets.search');
        Route::resource('tickets', TicketsController::class);
        Route::get('tickets/{tickets}/export', [TicketsController::class, 'export'])->name('tickets.export');

        /*
         * Contacts
         */
        Route::get('contacts/search', [ContactsController::class, 'search'])->name('contacts.search');
        Route::resource('contacts', ContactsController::class);
        Route::get('contacts/{contacts}/export', [ContactsController::class, 'export'])->name('contacts.export');

        /*
         * Netblocks
         */
        Route::get('netblocks/search', [NetblocksController::class, 'search'])->name('netblocks.search');
        Route::resource('netblocks', NetblocksController::class);
        Route::get('netblocks/{netblocks}/export', [NetblocksController::class, 'export'])->name('netblocks.export');

        /*
         * Domains
         */
        Route::get('domains/search', [DomainsController::class, 'search'])->name('domains.search');
        Route::resource('domains', DomainsController::class);
        Route::get('domains/{domains}/export', [DomainsController::class, 'export'])->name('domains.export');

        /*
         * Accounts
         */
        Route::get('accounts/search', [AccountsController::class, 'search'])->name('accounts.search');
        Route::resource('accounts', AccountsController::class);

        /*
         * Users
         */
        Route::get('users/search', [UsersController::class, 'search'])->name('users.search');
        Route::resource('users', UsersController::class);

        /*
         * Events
         */
        Route::get('events/search', [EventsController::class, 'search'])->name('events.search');
        Route::resource('events', EventsController::class, ['only' => ['index', 'show']]);

        /*
         * Notes
         */
        Route::resource('notes', NotesController::class, ['only' => ['store', 'update', 'destroy']]);

        /*
         * Search
         */
        Route::get('search', [SearchController::class, 'index'])->name('search');

        /*
         * AshLink
         */
        Route::resource('ash_link', AshLinksController::class);
    });
});
