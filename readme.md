## Version 5.0 is DEV and NOT working !

TODO
- TAG erors // MISSING-ABUSEIO5
- Update composer.json legacy classmap into psr4 (but errors when done)
- update public/js/*
- Do updates from LTS 6.x towards 12.x (OMG)
  TODO From 8.0 -> 9.0 NEXT
- Check "ddeboer/data-import": "^0.20.0" (NO LONGER MAINTAINED, REMOVED PROD)
- laminas/laminas-json (NO LONGER MAINTAINED, REMOVED PROD)
- wpb/string-blade-compiler (NO LONGER MAINTAINED, REMOVED PROD)
- fzaninotto/faker (NO LONGER MAINTAINED, REMOVED DEV)

- fix dependancy error for abuseio/hook-delegate (FOR NOW REMOVED)
- fix depdenancy error for abuseio/iodef (FOR NOW REMOVED)

- jover/singleton (still works?)
- kruisdraad/phpmailer (might need update)

---
Symfony Console, which is the underlying component that powers Artisan, expects all commands to return an integer. Therefore, you should ensure that any of your commands which return a value are returning integers:

public function handle()
{
    // Before...
    return true;
 
    // After...
    return 0;
}



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
