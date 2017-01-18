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
     * @return void
     */
    public function boot()
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
     * @return void
     */
    public function register()
    {
        //
    }
}
