<?php
/******************************************************************************
* AbuseIO 3.0
* Copyright (C) 2015 AbuseIO Development Team (http://abuse.io)
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software Foundation
* Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
*******************************************************************************
*
* Core loader which will be loading all required subsets when needed
*
******************************************************************************/

define('VERSION', '3.0.0-rc1');
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
                "collectors"        => array (
                                                "osint",
                                                "snds",
                                                "rblscan",
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

define_configuration(APP."/etc/settings.conf");

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

logger(LOG_DEBUG, "Loader version ". VERSION ." completed successfully");
?>
