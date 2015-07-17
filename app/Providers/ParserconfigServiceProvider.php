<?php

namespace AbuseIO\Providers;

use AbuseIO\Parsers\Factory;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\ClassLoader\ClassMapGenerator;

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
        $parserList = Factory::getParsers();
        foreach ($parserList as $parser) {
            $this->mergeConfigFrom(
                base_path().'/vendor/abuseio/parser-'.strtolower($parser)."/config/{$parser}.php",
                $parser
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
