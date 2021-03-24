# AbuseIO 4.3.0 Release notes

## Enhancements

## Changes 

- Upgraded Laravel engine to version 6.20.x (from 5.6) which includes support for composer2
- Replaced unmaintained packages by referred replacements
- Upgraded subpackages to latest version compatible with engine v6

## Bug fixes

- Fixed a Hydrate error, by casting it to proper array
- Fixed iodef depricated method usage (https://github.com/marknl/iodef/pull/4)

## Known issues

### Absolete packages
- wpb/string-blade-compiler is no longer avaiable in the future and must be replaced
- zendframework/* packages are no longer avaiable in the future and must be replaced

### Broken parsers
- feedback loop parser is returning errors on all sampels, unknown reasons
- spamexperts samples 3 t/m 9 is returning errors on all sampels, unknown reasons

