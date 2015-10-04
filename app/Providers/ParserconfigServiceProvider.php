<?php

namespace AbuseIO\Providers;

use AbuseIO\Parsers\Factory;
use Illuminate\Support\ServiceProvider;
use File;

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
            $basePath = base_path().'/vendor/abuseio/parser-'.strtolower($parser) . '/config';

            $parserConfig = $basePath . "/{$parser}.php";
            if (File::exists($parserConfig)) {
                $this->mergeConfigFrom(
                    $parserConfig,
                    'parsers.' . $parser
                );
            }

            $parserOverride = $basePath . '/' . app()->environment() . "/{$parser}.php";
            if (File::exists($parserOverride)) {
                $this->mergeConfigFrom(
                    $parserOverride,
                    app()->environment() . '.parsers.' . $parser
                );
            }
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
