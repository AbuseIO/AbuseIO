<?php

namespace AbuseIO\Providers;

use AbuseIO\Models\Contact;
use Illuminate\Support\ServiceProvider;

/**
 * Class ContactServiceProvider.
 */
class ContactServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     */
    public function boot(): void
    {
        // register a saving event listener on Ticket
        // which will add the ash tokens
        Contact::deleting(function ($contact) {
            $contact->notificationMethods()->delete();
        });
    }

    /**
     * Register any application services.
     *
     */
    public function register(): void
    {
        //
    }
}
