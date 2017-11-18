<?php

namespace AbuseIO\Providers;

use AbuseIO\Models\Account;
use Illuminate\Support\ServiceProvider;

class SystemAdminManagerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Account::saving(function ($current) {
            /** @var Account $current */
            if ($current->isSystemAccount()) {
                $account = Account::where('systemaccount', 1)
                    ->where('id', '!=', $current->id)
                    ->first();

                if ($account !== null) {
                    $account->update(['systemaccount' => false]);
                }
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
