# AbuseIO 5.0.0 Release notes

## Enhancements

- Switch Laravel from mysql to mariadb native engine
- Changed support into PHP8.4 (which is the minimum requirement now)
- Updated /admin/tickets, sorting shows default state, last-updated, ip/domain owner and shows unread notes
- Classifications now have aliasses, so wrongly named classifications can be fixed and duplicate code removed
- improve layout for modern screen resolutions and accomidate for extra fields in tables

## Changes 

- Upgraded Laravel engine to version 12.x (from 6.x) 
-- all legacy coding styles from Laravel have been addressed, using 12.x coding, where found.
-- $auth_user replaced with $auth()->user(), new coding style
-- CLI commands have different exit results (as of Laravel 8.x) which has been replaced with a helper.
-- CSV via ddeboer/data-import has been migrated into native CSV handling from PHP8
- Upgraded all dependancies to their latest version or replaced them
-- Moved from Uuid to Str::Uuid
- Replaced unmaintained packages by referred replacements
- Removed packages that do not have any referred replacements and refactored code
- Upgraded subpackages to latest version compatible with engine v12

## Security fixes

- Updated public/js and public/css components to their latest version

## Bug fixes

- Fixed bug where you could not change language properly
- Fixed bug and add guard where mailarchive directories where not setting chmod properly
- Fixed bug in iodef and singleton packages and moved them into AbuseIO repo for better management

## Known issues

- API is 'wonky', it was never fully tested, but it needs to be. For now its experimental at best.

- PHPmailuser needs to be migrated from kruisdraad to abuseio repo, and changes backported (while retaining our patches)
- abuseio-docker is broken, at this point set to be removed in favour if Ansible
- abuseio-ansible needs to be updated for 5.0

- netcraft new samples with xarf
- Shadowserver Report Changes #373


### Absolete packages

### Broken parsers

