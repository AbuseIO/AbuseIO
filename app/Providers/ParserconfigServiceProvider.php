<?php

namespace AbuseIO\Providers;

use AbuseIO\Parsers\Factory;
use Illuminate\Support\ServiceProvider;

class ParserconfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files of all installed Parsers
        // All parser configs we will put into the master 'parsers' tree of the config
        // So we can easily walk through all of them based on the active configuration
        $parserList = Factory::getParsers();
        foreach ($parserList as $parser) {
            $this->mergeConfigFrom(
                base_path().'/vendor/abuseio/parser-'.strtolower($parser)."/config/{$parser}.php",
                'parsers.' . $parser
            );
        }
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
