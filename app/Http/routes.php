<?php

use AbuseIO\Http\Controllers\AccountsController;
use AbuseIO\Http\Controllers\AshLinksController;
use AbuseIO\Http\Controllers\AshController;
use AbuseIO\Http\Controllers\Auth\LoginController;
use AbuseIO\Http\Controllers\ContactsController;
use AbuseIO\Http\Controllers\DomainsController;
use AbuseIO\Http\Controllers\EventsController;
use AbuseIO\Http\Controllers\NetblocksController;
use AbuseIO\Http\Controllers\NotesController;
use AbuseIO\Http\Controllers\SearchController;
use AbuseIO\Http\Controllers\TicketsController;
use AbuseIO\Http\Controllers\UsersController;
use AbuseIO\Http\Controllers\BrandsController;
use AbuseIO\Http\Controllers\IncidentsController;
use AbuseIO\Http\Controllers\ProfileController;
use AbuseIO\Http\Controllers\LocaleController;
use AbuseIO\Http\Controllers\EvidenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/admin/home');
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
    Route::group(['middleware' => ['web', 'auth', 'permission:login_portal'], 'as' => 'admin.'], function () {
        Route::get('/home', function () {
            return view('home');
        })->name('home');

        /*
         * Locale
         */
        Route::get('locale/{locale}', [LocaleController::class, 'setLocale'])->name('locale');

        /*
         * Profile
         */
        Route::get('profile', [ProfileController::class, 'edit'])->middleware('permission:profile_manage')->name('profile.index');
        Route::patch('profile/{profile}', [ProfileController::class, 'update'])->middleware('permission:profile_manage')->name('profile.update');
        Route::put('profile/{profile}', [ProfileController::class, 'update'])->middleware('permission:profile_manage');

        /*
         * Tickets
         */
        Route::get('tickets/search', [TicketsController::class, 'search'])->middleware('permission:tickets_view')->name('tickets.search');
        Route::get('tickets', [TicketsController::class, 'index'])->middleware('permission:tickets_view')->name('tickets.index');
        Route::get('tickets/create', [TicketsController::class, 'create'])->middleware('permission:tickets_create')->name('tickets.create');
        Route::post('tickets', [TicketsController::class, 'store'])->middleware('permission:tickets_create')->name('tickets.store');
        Route::get('tickets/{tickets}', [TicketsController::class, 'show'])->middleware('permission:tickets_view')->name('tickets.show');
        Route::get('tickets/{tickets}/edit', [TicketsController::class, 'edit'])->middleware('permission:tickets_edit')->name('tickets.edit');
        Route::put('tickets/{tickets}', [TicketsController::class, 'update'])->middleware('permission:tickets_edit')->name('tickets.update');
        Route::patch('tickets/{tickets}', [TicketsController::class, 'update'])->middleware('permission:tickets_edit');
        Route::delete('tickets/{tickets}', [TicketsController::class, 'destroy'])->middleware('permission:tickets_delete')->name('tickets.destroy');
        Route::get('tickets/export/{format}', [TicketsController::class, 'export'])->middleware('permission:tickets_export')->name('tickets.export');

        /*
         * Ticket contact update (GET)
         */
        Route::get('tickets/{tickets}/update/{who?}', [TicketsController::class, 'update'])->middleware('permission:tickets_edit')->name('tickets.contact_update');

        /*
         * Notifications
         */
        Route::get('tickets/{tickets}/notify/{who?}', [TicketsController::class, 'notify'])->middleware('permission:tickets_edit')->name('tickets.notify');

        /*
         * Ticket status
         */
        Route::get('tickets/{tickets}/status/{status}', [TicketsController::class, 'status'])->middleware('permission:tickets_edit')->name('tickets.status');

        /*
         * Incidents
         */
        Route::get('incidents/create', [IncidentsController::class, 'create'])->middleware('permission:incidents_create')->name('incidents.create');
        Route::post('incidents', [IncidentsController::class, 'store'])->middleware('permission:incidents_create')->name('incidents.store');

        /*
         * Contacts
         */
        Route::get('contacts/search', [ContactsController::class, 'search'])->middleware('permission:contacts_view')->name('contacts.search');
        Route::get('contacts', [ContactsController::class, 'index'])->middleware('permission:contacts_view')->name('contacts.index');
        Route::get('contacts/create', [ContactsController::class, 'create'])->middleware('permission:contacts_create')->name('contacts.create');
        Route::post('contacts', [ContactsController::class, 'store'])->middleware('permission:contacts_create')->name('contacts.store');
        Route::get('contacts/{contacts}', [ContactsController::class, 'show'])->middleware('permission:contacts_view')->name('contacts.show');
        Route::get('contacts/{contacts}/edit', [ContactsController::class, 'edit'])->middleware('permission:contacts_edit')->name('contacts.edit');
        Route::put('contacts/{contacts}', [ContactsController::class, 'update'])->middleware('permission:contacts_edit')->name('contacts.update');
        Route::patch('contacts/{contacts}', [ContactsController::class, 'update'])->middleware('permission:contacts_edit');
        Route::delete('contacts/{contacts}', [ContactsController::class, 'destroy'])->middleware('permission:contacts_delete')->name('contacts.destroy');
        Route::get('contacts/export/{format}', [ContactsController::class, 'export'])->middleware('permission:contacts_export')->name('contacts.export');

        /*
         * Netblocks
         */
        Route::get('netblocks/search', [NetblocksController::class, 'search'])->middleware('permission:netblocks_view')->name('netblocks.search');
        Route::get('netblocks', [NetblocksController::class, 'index'])->middleware('permission:netblocks_view')->name('netblocks.index');
        Route::get('netblocks/create', [NetblocksController::class, 'create'])->middleware('permission:netblocks_create')->name('netblocks.create');
        Route::post('netblocks', [NetblocksController::class, 'store'])->middleware('permission:netblocks_create')->name('netblocks.store');
        Route::get('netblocks/{netblocks}', [NetblocksController::class, 'show'])->middleware('permission:netblocks_view')->name('netblocks.show');
        Route::get('netblocks/{netblocks}/edit', [NetblocksController::class, 'edit'])->middleware('permission:netblocks_edit')->name('netblocks.edit');
        Route::put('netblocks/{netblocks}', [NetblocksController::class, 'update'])->middleware('permission:netblocks_edit')->name('netblocks.update');
        Route::patch('netblocks/{netblocks}', [NetblocksController::class, 'update'])->middleware('permission:netblocks_edit');
        Route::delete('netblocks/{netblocks}', [NetblocksController::class, 'destroy'])->middleware('permission:netblocks_delete')->name('netblocks.destroy');
        Route::get('netblocks/export/{format}', [NetblocksController::class, 'export'])->middleware('permission:netblocks_export')->name('netblocks.export');

        /*
         * Domains
         */
        Route::get('domains/search', [DomainsController::class, 'search'])->middleware('permission:domains_view')->name('domains.search');
        Route::get('domains', [DomainsController::class, 'index'])->middleware('permission:domains_view')->name('domains.index');
        Route::get('domains/create', [DomainsController::class, 'create'])->middleware('permission:domains_create')->name('domains.create');
        Route::post('domains', [DomainsController::class, 'store'])->middleware('permission:domains_create')->name('domains.store');
        Route::get('domains/{domains}', [DomainsController::class, 'show'])->middleware('permission:domains_view')->name('domains.show');
        Route::get('domains/{domains}/edit', [DomainsController::class, 'edit'])->middleware('permission:domains_edit')->name('domains.edit');
        Route::put('domains/{domains}', [DomainsController::class, 'update'])->middleware('permission:domains_edit')->name('domains.update');
        Route::patch('domains/{domains}', [DomainsController::class, 'update'])->middleware('permission:domains_edit');
        Route::delete('domains/{domains}', [DomainsController::class, 'destroy'])->middleware('permission:domains_delete')->name('domains.destroy');
        Route::get('domains/export/{format}', [DomainsController::class, 'export'])->middleware('permission:domains_export')->name('domains.export');

        /*
         * Accounts
         */
        Route::get('accounts/search', [AccountsController::class, 'search'])->middleware('permission:accounts_view')->name('accounts.search');
        Route::get('accounts', [AccountsController::class, 'index'])->middleware('permission:accounts_view')->name('accounts.index');
        Route::get('accounts/create', [AccountsController::class, 'create'])->middleware('permission:accounts_create')->name('accounts.create');
        Route::post('accounts', [AccountsController::class, 'store'])->middleware('permission:accounts_create')->name('accounts.store');
        Route::get('accounts/{accounts}', [AccountsController::class, 'show'])->middleware('permission:accounts_view')->name('accounts.show');
        Route::get('accounts/{accounts}/edit', [AccountsController::class, 'edit'])->middleware('permission:accounts_edit')->name('accounts.edit');
        Route::put('accounts/{accounts}', [AccountsController::class, 'update'])->middleware('permission:accounts_edit')->name('accounts.update');
        Route::patch('accounts/{accounts}', [AccountsController::class, 'update'])->middleware('permission:accounts_edit');
        Route::delete('accounts/{accounts}', [AccountsController::class, 'destroy'])->middleware('permission:accounts_delete')->name('accounts.destroy');
        Route::get('accounts/{accounts}/enable', [AccountsController::class, 'enable'])->middleware('permission:accounts_enable')->name('accounts.enable');
        Route::get('accounts/{accounts}/disable', [AccountsController::class, 'disable'])->middleware('permission:accounts_disable')->name('accounts.disable');

        /*
         * Users
         */
        Route::get('users/search', [UsersController::class, 'search'])->middleware('permission:users_view')->name('users.search');
        Route::get('users', [UsersController::class, 'index'])->middleware('permission:users_view')->name('users.index');
        Route::get('users/create', [UsersController::class, 'create'])->middleware('permission:users_create')->name('users.create');
        Route::post('users', [UsersController::class, 'store'])->middleware('permission:users_create')->name('users.store');
        Route::get('users/{users}', [UsersController::class, 'show'])->middleware('permission:users_view')->name('users.show');
        Route::get('users/{users}/edit', [UsersController::class, 'edit'])->middleware('permission:users_edit')->name('users.edit');
        Route::put('users/{users}', [UsersController::class, 'update'])->middleware('permission:users_edit')->name('users.update');
        Route::patch('users/{users}', [UsersController::class, 'update'])->middleware('permission:users_edit');
        Route::delete('users/{users}', [UsersController::class, 'destroy'])->middleware('permission:users_delete')->name('users.destroy');
        Route::get('users/{users}/enable', [UsersController::class, 'enable'])->middleware('permission:users_enable')->name('users.enable');
        Route::get('users/{users}/disable', [UsersController::class, 'disable'])->middleware('permission:users_disable')->name('users.disable');

        /*
         * Brands
         */
        Route::get('brands/search', [BrandsController::class, 'search'])->middleware('permission:brands_view')->name('brands.search');
        Route::get('brands', [BrandsController::class, 'index'])->middleware('permission:brands_view')->name('brands.index');
        Route::get('brands/create', [BrandsController::class, 'create'])->middleware('permission:brands_create')->name('brands.create');
        Route::post('brands', [BrandsController::class, 'store'])->middleware('permission:brands_create')->name('brands.store');
        Route::get('brands/{brands}', [BrandsController::class, 'show'])->middleware('permission:brands_view')->name('brands.show');
        Route::get('brands/{brands}/edit', [BrandsController::class, 'edit'])->middleware('permission:brands_edit')->name('brands.edit');
        Route::put('brands/{brands}', [BrandsController::class, 'update'])->middleware('permission:brands_edit')->name('brands.update');
        Route::patch('brands/{brands}', [BrandsController::class, 'update'])->middleware('permission:brands_edit');
        Route::delete('brands/{brands}', [BrandsController::class, 'destroy'])->middleware('permission:brands_delete')->name('brands.destroy');
        Route::get('logo/{id}', [BrandsController::class, 'logo'])->middleware('permission:brands_view')->name('logo');
        Route::post('brands/{brands}/activate', [BrandsController::class, 'activate'])->middleware('permission:brands_edit')->name('brands.activate');

        /*
         * Events
         */
        //Route::get('events/search', [EventsController::class, 'search'])->name('events.search');
        //Route::resource('events', EventsController::class, ['only' => ['index', 'show']]);

        /*
         * Notes
         */
        Route::resource('notes', NotesController::class, ['only' => ['store', 'update', 'destroy']]);

        /*
         * Search
         */
        //Route::get('search', [SearchController::class, 'index'])->name('search');

        /*
         * Evidence
         */
        Route::get('evidence/{evidence}', [EvidenceController::class, 'show'])->middleware('permission:evidence_view')->name('evidence.show');
        Route::get('evidence/{evidence}/download', [EvidenceController::class, 'download'])->middleware('permission:evidence_view')->name('evidence.download');
        Route::get('evidence/{evidence}/attachment/{file}', [EvidenceController::class, 'attachment'])->middleware('permission:evidence_view')->name('evidence.attachment');
    });
});

// Public ASH routes
Route::group(['prefix' => 'ash'], function () {
    // ASH ticket page
    Route::get('collect/{ticketID}/{token}', [AshController::class, 'index'])
        ->middleware(['ash.token'])
        ->name('ash.show');

    // ASH add note (POST)
    Route::post('collect/{ticketID}/{token}', [AshController::class, 'addNote'])
        ->middleware(['ash.token'])
        ->name('ash.addnote');

    // Public locale switch for ASH
    Route::get('locale/{locale}', [LocaleController::class, 'setLocale'])
        ->name('ash.locale');

    // Brand logo for ASH
    Route::get('logo/{id}', [BrandsController::class, 'logo'])
        ->name('ash.logo');
});

Route::model('contacts', \AbuseIO\Models\Contact::class, function () { throw new \Illuminate\Database\Eloquent\ModelNotFoundException(); });
Route::model('netblocks', \AbuseIO\Models\Netblock::class, function () { throw new \Illuminate\Database\Eloquent\ModelNotFoundException(); });
Route::model('domains', \AbuseIO\Models\Domain::class, function () { throw new \Illuminate\Database\Eloquent\ModelNotFoundException(); });
Route::model('tickets', \AbuseIO\Models\Ticket::class, function () { throw new \Illuminate\Database\Eloquent\ModelNotFoundException(); });
Route::model('evidence', \AbuseIO\Models\Evidence::class, function () { throw new \Illuminate\Database\Eloquent\ModelNotFoundException(); });
Route::model('users', \AbuseIO\Models\User::class, function () { throw new \Illuminate\Database\Eloquent\ModelNotFoundException(); });
Route::model('brands', \AbuseIO\Models\Brand::class, function () { throw new \Illuminate\Database\Eloquent\ModelNotFoundException(); });
Route::model('accounts', \AbuseIO\Models\Account::class, function () { throw new \Illuminate\Database\Eloquent\ModelNotFoundException(); });
