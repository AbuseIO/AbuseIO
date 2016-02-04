# Notes before you start

- You can safely ignore upgrading procedures from versions 1.x or 2.x as these were BIT-only and never released to 
the public.

- If you are running 3.0, please upgrade to the latest version from the GIT repository (tagged dev-master) before
starting the upgrade to 4.0. The migration tool has _only_ been tested with this version and previous versions
might result in errors

- If for any reason the migration stops, you can fix the problem, empty the NEW (4.0) database and then try again

- The ASH authentication tokens have changed in format resulting in all old 3.0 tokens being invalid. This cannot be
resolved. Once your migration is completed you're advised to send out notifications for all the open tickets to their
contacts to get the updated ASH token/url.

# Before you start

For the preparation phase you can leave you old installation in production. A cache will be built which can take up to 
several hours, perhaps even days depending on the amount of evidence. The cache is basically a new dump of parsed 
incidents with the new parser modules which might result in additional data, as a lot of fields were excluded in 
previous versions.

In the cases where the new data cannot be matched with the old data entirely, only the infomartion blob will be 
used to replay evidence and if the ticket count is e.g. 10, it will be stored 10 times in the new incident 
format to match the new database schema. The view would be exactly the same, however if the new parser was 
successful in parsing the old evidence then _additional_ (left out) data from evidence will be shown in 
these tickets. 

Migrating the rest of data will take a long time, because all the evidence and their links have to be replayed so
that the locally stored files are all in the new format. 

To give you some kind of indication: 
- This preperation process takes about 1,5 hours with ~8000 evidences in the table.
- The migration process having around 900.000 entries in the evidence links would take a VM arround 22 
hours. This would be a medium sized ISP running 3.0 for more than one year. Having more RAM, SSH storage, 
tuned MySQL, etc will reduce the time required for this migration.

# Requirements

- create a readonly user to the old abuseio 3.x database. All the data required is stored in the database. The old 
database wil not need any changes, but if you're bold you can use the old 3.x credentials to the database.

- in the config/database.php file create a copy of the 'mysql' element and name it abuseio3 with the server, 
user and password to access the database.

# Preperations (-p / --prepare)

- make sure you followed the installation guidelines correctly and setup the default account (system account). 
The default system account is required and all data will be linked to this account!

- run the cache builder (in a screen):

```bash
cd $INSTALLDIR
php artisan migrate:oldversion --prepare
```

Progress will be indicated, but consider this task to take a lot of time.

# Migration start (-s / --start)

Now starting the actual migration, creating tickets from the cache.
 
## Actions on the old server

You must stop EVERYTHING on the old server to prevent changes, EXCEPT mysql. (so stuff like cron, webserver, mta, 
etc). From this point, you are in maintenance mode.


## Actions on the new server

- make sure the supervisored daemons have been stopped
- make sure the webserver has been stopped
- make sure the MTA has been stopped
- make sure the cron task has been disabled (housekeeper)

- Now run the cache builder again for any changes that might have happened since the last run, this will not take 
long if there aren't a lot of changes:

```bash
cd $INSTALLDIR
php artisan migrate:oldversion --prepare
```

- Finally kick off the migration start

```bash
cd $INSTALLDIR
php artisan migrate:oldversion --start
```

Again this might take a while depending on how deep existing evidence was linked. A progress incidator is shown.

# Post installation

Once your migration has been completed without errors (and you will notice them because they are big and red) you 
can start up all the services again (supervisord, webserver, mta, etc).

If you haven't done so, you must fix the MTA settings so the notifications will end up at the new installation
which might also require you to change a few aliases/procmails/etc if you used them.
