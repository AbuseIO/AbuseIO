Note: You can safely ignore upgrading procedures from versions 1.x or 2.x as these were BIT only and never released to the public.

Note: If you are running 3.0, please upgrade to 3.1 just to be sure!

Preface

Until stated you can leave you old installation in production. A cache will be build first which takes up to several hours, perhaps even days depending on the amount of evidence.

The cache is basically a new dump of parsed incidents with the new parser modules which might result in additional data, as a lot of fields where excluded in previous versions.

In the cases the new data cannot be matched with the old data entirely, then only the infomartion blob will be used to replay evidence and if the ticket count is e.g. 10, it will be stored 10 times in the new incident format to match the new database schema. The view would be exactly the same, however if the new parser was successfully _additional_ (left out) data from evidence will be shown in these tickets.

Requirements

- create a readonly user to the old abuseio 3.x database. All the data required is stored in the database. The old database wil not need any changes

Preperations

- make sure you followed the installation guidelines correctly and setup the default account/brand. The name of the default account is required and all data will be linked to this account.

- run the cache builder (in a screen):

php artisan migrate:oldversion -p --account default

A progress will be indicated, but consider this task to take a lot of time.



Migration start (on the old 3.x server)

Now starting the actual migration, creating tickets from the cache, you must stop EVERYTHING on the old server to prevent changes, EXCEPT mysql. (so stuff like cron, webserver, mta, etc). From this point, you are in maintaince mode.

Migration start (on the new 4.x server)
- make sure the supervisored daemons have been stopped
- make sure the webserver has been stopped
- make sure the MTA has been stopped

- Now run the cache builder again for any changes that might have happend since the last run, this wouldn't take long if there are not a lot of changes:

php artisan migrate:oldversion -p --account default


- Finally kick of the migartion start

php artisan migrate:oldversion -s --account default

Again this might take a while depending on how deep existing evidence was linked. A progress incidator is shown
