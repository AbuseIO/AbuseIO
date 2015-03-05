<?php
define('VERSION', '3.0.0');
define('APP',realpath(dirname(__FILE__).'/../../'));

// Globally used options like debug and version

// getopt() only works on cli
if (PHP_SAPI == 'cli') {
    $shortopts = "dv";
    $longopts  = array(
                        "debug",
                        "version",
                      );
    $options   = getopt($shortopts, $longopts);

    if (isset($options['debug']) || isset($options['d'])) {
        define('DEBUG', true);
    } else {
        define('DEBUG', false);
    }
    if (isset($options['version']) || isset($options['v'])){
        die(VERSION . PHP_EOL);
    }
}

// Modules that should be loaded for AbuseIO to function correctly
$modules = array(
                "generic"           => array (
                                                "config",
                                                "logger",
                                                "functions",
                                                "mda",
                                                "mailer",
                                             ),
                "database"          => array (
                                                "mysql",
                                             ),
                "parsers"           => array (
                                                "csv",
                                             ),
                "core"              => array (
                                                "evidence",
                                                "reporting",
                                                "notes",
                                                "customers",
                                                "netblocks"
                                             ),
                );


// Load static modules
foreach ($modules as $library => $objects) {
    foreach ($objects as $object) {
        include(APP."/lib/${library}/${object}.php");
    }
}

define_configuration();

// Load dynamic custom modules
if(defined('CUSTOM_MODULES')) {
    foreach(explode(",", CUSTOM_MODULES) as $object) {
        if(is_file(APP."/lib/custom/${object}.php")) {
            include(APP."/lib/custom/${object}.php");
        } else {
            logger(LOG_ERR, "Loader was not able to load custom module ${object}");
            exit(1);
        }
    }
}

date_default_timezone_set(TIME_ZONE);

logger(LOG_DEBUG, "Loader completed successfully");
?>
