## Version 5.0 is DEV and NOT working !

TODO
- TAG erors // MISSING-ABUSEIO5

- Do updates from LTS 6.x towards 12.x (OMG)

- Check "ddeboer/data-import": "^0.20.0" (NO LONGER MAINTAINED, REMOVED PROD)
- laminas/laminas-json (NO LONGER MAINTAINED, REMOVED PROD)
- wpb/string-blade-compiler (NO LONGER MAINTAINED, REMOVED PROD)
- fzaninotto/faker (NO LONGER MAINTAINED, REMOVED DEV)

- fix dependancy error for abuseio/hook-delegate (FOR NOW REMOVED)
- fix depdenancy error for abuseio/iodef (FOR NOW REMOVED)

- jover/singleton (still works?)
- kruisdraad/phpmailer (might need update)

Due to:
- Package laminas/laminas-loader is abandoned, you should avoid using it. No replacement was suggested.
- Package laminas/laminas-math is abandoned, you should avoid using it. No replacement was suggested.
Removed:
                "laminas/laminas-http": "2.22.*",
                "laminas/laminas-xmlrpc": "2.21.*"

Autoload errors during composer install
```
Generating optimized autoload files
Class tests\Api\Account\ApiVersionTest located in ./tests/Api/ApiVersionTest.php does not comply with psr-4 autoloading standard (rule: tests\ => ./tests). Skipping.
Class AbuseIO\Console\Commands\Domain\CreateCommandTest located in ./tests/Console/Commands/Domain/CreateCommandTest.php does not comply with psr-4 autoloading standard (rule: tests\ => ./tests). Skipping.
Class App\Logging\AbuseIOFormatter located in ./app/Logging/AbuseIOFormatter.php does not comply with psr-4 autoloading standard (rule: AbuseIO\ => ./app). Skipping.
Class App\Providers\BroadcastServiceProvider located in ./app/Providers/BroadcastServiceProvider.php does not comply with psr-4 autoloading standard (rule: AbuseIO\ => ./app). Skipping.
```


## AbuseIO - Abusemanagement tools

[![StyleCI](https://styleci.io/repos/31737623/shield?style=flat&branch=4.0)](https://styleci.io/repos/31737623?branch=4.0)
[![Build Status](https://api.travis-ci.org/AbuseIO/AbuseIO.svg)](https://travis-ci.org/AbuseIO/AbuseIO)
[![Total Downloads](https://poser.pugx.org/abuseio/abuseio/d/total.svg)](https://packagist.org/packages/abuseio/abuseio)
[![Latest Stable Version](https://poser.pugx.org/abuseio/abuseio/v/stable.svg)](https://packagist.org/packages/abuseio/abuseio)
[![Latest Unstable Version](https://poser.pugx.org/abuseio/abuseio/v/unstable.svg)](https://packagist.org/packages/abuseio/abuseio)
[![License](https://poser.pugx.org/abuseio/abuseio/license.svg)](https://packagist.org/packages/abuseio/abuseio)

AbuseIO is a toolkit to receive, process, correlate and notify end users about abuse reports received by network operators, typically
hosting and access providers. The purpose is to consolidate efforts by various companies and individuals to automate and improve
the abuse handling process.

## Official Documentation

Documentation for AbuseIO can be found in [its own repository](https://www.github.com/abuseio/abuseio-docs/).

An online version of the documentation can be found on https://docs.abuse.io/

## Contributing

Thank you for considering contributing to AbuseIO! The contribution guide can be found in the [AbuseIO website](https://abuse.io/community/get-involved/).

## Security Vulnerabilities

If you discover a security vulnerability within AbuseIO, please send an e-mail to the AbuseIO CERT at cert@abuse.io (GPG available on Key servers). All security vulnerabilities will be promptly addressed.

### License

AbuseIO is open-sourced software licensed under the [GNUv2 license](http://www.gnu.org/licenses/gpl-2.0.en.html)
