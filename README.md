# This is a development branch for 4.0

Do NOT use this for any kind of production

If you are looking for the latest stable version, you will find the branch here:

https://github.com/AbuseIO/AbuseIO/tree/AbuseIO-3.0

# Requirements

PHP 5.x or better
MTA that can redirect into pipes (e.g. Exim or Postfix)
Apache 2.x or better
Database backend (mysql, postgres, etc)
Beanstalk Queueing server

for ubuntu

```bash
apt-get install php5 mysql-server beanstalkd apache2 postfix supervisor bind9
```

# Installation (as root)

## Setup local resolving

Some parsers produce high amounts of DNS queries, so your better off using a local resolve (e.g. bind)
in the above install example bind is installed and you only need to update your /etc/resolv.conf (or
with newer ubuntu versions the /etc/network/interfaces) to use 127.0.0.1 as the FIRST resolver, but make
sure you leave a 2nd or 3rd with your 'normale' resolvers.

## Setup supervisor:

##### /etc/supervisor/conf.d/abuseio_queue_email.conf
```
[program:abuseio_queue_emails]
command=php artisan queue:listen --tries=1 --sleep=3 --memory=128 --delay=0 --queue=emails
directory=/opt/abuseio
stdout_logfile=/opt/abuseio/storage/logs/queue-emails.log
redirect_stderr=true
```

then:

```bash
supervisorctl reread  
supervisorctl add abuseio_queue_emails
supervisorctl start abuseio_queue_emails
```

## Install global composer 

```bash
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

## Install global dependencies

```bash
pecl install mailparse (note: mbstring should be installed on linux systems, if not add mbstring to the install command)
echo "extension=mailparse.so" > /etc/php5/mods-available/mailparse.ini
php5enmod mailparse
```

## Create local user 

```bash
adduser abuseio
```

## checkout git

```bash
cd /opt
git clone -b AbuseIO-4.0 git@github.com:AbuseIO/AbuseIO.git abuseio
```
## fix permissions

```bash
cd /opt
chown -R abuseio:abuseio abuseio
chmod -R 775 abuseio/storage
chown -R abuseio:www-data abuseio/storage
chown -R abuseio:postfix abuseio/storage/mailarchive
chown abuseio:www-data abuseio/bootstrap/cache
chmod 775 abuseio/bootstrap/cache
```

## Creating MTA delivery

To test your mail route, send a e-mail to notifier@your-MTA-domain.lan and if you want to use the CLI you can directly
send a EML file into the parser using:

```bash
cat file.eml | /usr/bin/php -q /opt/abuseio/artisan --env=local email:parse
```

Please note that using the 'cat' option might give you a difference with email bodies, for example with line 
termination you'd see a \r\n instead of \n or vice versa. Once you have tested your parser by using the cat method
always use the MTA address to validate your work!

### Postfix
 
```bash
echo 'notifier: | "| /usr/bin/php -q /opt/abuseio/artisan --env=local email:parse"' >> /etc/aliasses
newaliasses
```

## Configuring Apache

You should be able to visit the website at URI /admin/ with a document root at /opt/abuseio/public/

Don't forget: It's highly recommended to add a .htaccess and secure with both IP as ACL filters!

# Installation (as abuseio)

## Install dependencies

```bash
cd /opt/abuseio
composer install
```

## Setting up configuration

Create the file /opt/abuseio/.env with the following hints:

```bash
APP_ENV=local (change to production if needed)
APP_DEBUG=true (change to false if needed)
APP_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
DB_HOST=localhost
DB_DATABASE=abuseio
DB_USERNAME=username
DB_PASSWORD=password
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync
```

## Installing and seeding database

```bash
cd /opt/abuseio
php artisan migrate:install
php artisan migrate
php artisan db:seed
```
