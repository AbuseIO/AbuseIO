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

- make sure you have disabled the cron (housekeeper)! if you do not disable this it will start to prune data
while migration is running because it would think the data is unlinked. That is true, but during the migration
these links are being created which takes time!

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

## Running multithreaded:

This will start thread number 1, which calculates (based on size) itself to tickets 1 - 1000. If you start thread 2, you will get 1001 tru 2000, etc. You can check the ticket amount of tickets in your old installation and run the right amount of processes for the migration. On a 8 Core VM (1,8Ghz) and 8GB RAM tests were completed without problems with 25 threads, in batches of 1000 simultaniously.

In addition you could consider using a RAMDISK if your backend storage is not SSD. After the prepare process rename storage/migration/ to storage/migration-disk and check the size of this folder and create a RAMDISK of that same size +376MB reserve on the storage/migration/ place. Then copy all the files from the -disk folder to the ramdisk.

i have 7341 evidences to be prepared, therefor i need to spawn 8 chunks of 1000
i have 21520 tickets to generate, therefor i need to spam 22 chuncks of 1000, but i add more to be safe
After running all the commands i can screen -r to each thread to view the results, anything red is bad

Example:
```
screen -dmS pthread1  bash -c "php artisan migrate:oldversion -p --threaded --threadid=1  --threadsize=1000; exec bash"
screen -dmS pthread2  bash -c "php artisan migrate:oldversion -p --threaded --threadid=2  --threadsize=1000; exec bash"
screen -dmS pthread3  bash -c "php artisan migrate:oldversion -p --threaded --threadid=3  --threadsize=1000; exec bash"
screen -dmS pthread4  bash -c "php artisan migrate:oldversion -p --threaded --threadid=4  --threadsize=1000; exec bash"
screen -dmS pthread5  bash -c "php artisan migrate:oldversion -p --threaded --threadid=5  --threadsize=1000; exec bash"
screen -dmS pthread6  bash -c "php artisan migrate:oldversion -p --threaded --threadid=6  --threadsize=1000; exec bash"
screen -dmS pthread7  bash -c "php artisan migrate:oldversion -p --threaded --threadid=7  --threadsize=1000; exec bash"
screen -dmS pthread8  bash -c "php artisan migrate:oldversion -p --threaded --threadid=8  --threadsize=1000; exec bash"

php artisan migrate:oldversion -s --skiptickets --skipnotes

screen -dmS thread1  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=1  --threadsize=1000; exec bash"
screen -dmS thread2  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=2  --threadsize=1000; exec bash"
screen -dmS thread3  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=3  --threadsize=1000; exec bash"
screen -dmS thread4  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=4  --threadsize=1000; exec bash"
screen -dmS thread5  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=5  --threadsize=1000; exec bash"
screen -dmS thread6  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=6  --threadsize=1000; exec bash"
screen -dmS thread7  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=7  --threadsize=1000; exec bash"
screen -dmS thread8  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=8  --threadsize=1000; exec bash"
screen -dmS thread9  bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=9  --threadsize=1000; exec bash"
screen -dmS thread10 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=10 --threadsize=1000; exec bash"
screen -dmS thread10 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=10 --threadsize=1000; exec bash"
screen -dmS thread11 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=11 --threadsize=1000; exec bash"
screen -dmS thread12 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=12 --threadsize=1000; exec bash"
screen -dmS thread13 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=13 --threadsize=1000; exec bash"
screen -dmS thread14 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=14 --threadsize=1000; exec bash"
screen -dmS thread15 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=15 --threadsize=1000; exec bash"
screen -dmS thread16 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=16 --threadsize=1000; exec bash"
screen -dmS thread17 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=17 --threadsize=1000; exec bash"
screen -dmS thread18 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=18 --threadsize=1000; exec bash"
screen -dmS thread19 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=19 --threadsize=1000; exec bash"
screen -dmS thread20 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=20 --threadsize=1000; exec bash"
screen -dmS thread21 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=21 --threadsize=1000; exec bash"
screen -dmS thread22 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=22 --threadsize=1000; exec bash"
screen -dmS thread23 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=23 --threadsize=1000; exec bash"
screen -dmS thread24 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=24 --threadsize=1000; exec bash"
screen -dmS thread25 bash -c "php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skipnotes --threaded --threadid=25 --threadsize=1000; exec bash"

php artisan migrate:oldversion -s --skipcontacts --skipnetblocks --skiptickets
```

# Post installation

Once your migration has been completed without errors (and you will notice them because they are big and red) you 
can start up all the services again (supervisord, webserver, mta, etc).

If you haven't done so, you must fix the MTA settings so the notifications will end up at the new installation
which might also require you to change a few aliases/procmails/etc if you used them.
