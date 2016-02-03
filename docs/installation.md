# Installation Guide AbuseIO 4.0


## Index

1. [System Requirements](#requirements)
2. [Installation as root](#install_root)
3. [Installation as abuseio](#install_abuseio)


<a name="requirements"></a>
### 1. System Requirements

+ 64 Bits Linux based distribution
+ MTA (Postfix 2.9.1+, Exim 4.76+)
+ Webserver software (Apache 2.22+ or Nginx 1.1.19+)
+ Database backend (MySQL 5.5+, Postgres 9.1+)
+ PHP 5.5.9+ (apache module
+ (__optional__) Local resolving nameserver (Bind, pDNSRecursor) ([more info](#resolving))



<a name="install_root"></a>
### 2. Installation (as root)

#### Packages (ubuntu)
```bash
apt-get install curl git mysql-server apache2 apache2-utils postfix supervisor libapache2-mod-php5 php5 php-pear php5-dev php5-mcrypt php5-mysql php5-pgsql
```


#### Composer
Download the latest version of [composer](https://getcomposer.org/) and make it system wide accessible.
```bash
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```


#### Mailparse
This is a pecl module for php that has to be downloaded and compiled before you can use it.
```bash
pecl install mailparse-2.1.6
```
On some systems, the above command fails. If so, try adding -Z after 'install'.

```bash
echo "extension=mailparse.so" > /etc/php5/mods-available/mailparse.ini
php5enmod mailparse
php5enmod mcrypt
```


#### Create local user
We're creating a local user to run the application as.
```bash
adduser abuseio
```
Then add your apache user and MTA user to the abuseio group.  
Ubuntu defaults would then be:

```bash
addgroup abuseio abuseio
addgroup postfix abuseio
addgroup www-data abuseio
```
> You will need to restart apache and postfix in this example to make your changes active!


#### AbuseIO
You can install AbuseIO in a few ways. You can download a tarball or install with composer. Either way is fine.

> Keep note of the following:  
> - Updating/Installing packages with composer might require a GIT account and a generated token.
> - You should __NOT__ run the `composer update` command, unless you know exactly what you are doing.

##### Install stable from tarball
```bash
cd /opt
wget https://abuse.io/releases/abuseio-4.0.0.tar.gz
tar zxf abuseio-4.0.0.tar.gz
```

##### Install stable with composer
```bash
cd /opt
composer create-project abuseio/abuseio
```


#### Permission
Some parts of the installation had to be done as root and the application will run as user 'abuseio', so we need to set some permissions.

```bash
cd /opt/abuseio
chown -R abuseio:abuseio .
chmod -R 770 storage/
chmod 770 bootstrap/cache/
```


#### Supervisor, logrotate, rsyslog
This will setup supervisor, logrotate and rsyslog for you
```bash
cp -vr /opt/abuseio/extra/etc/* /etc/
mkdir /var/log/abuseio
chown syslog:adm /var/log/abuseio
supervisorctl reread
/etc/init.d/supervisor restart
service rsyslog restart
```
> Important: The supervisord worker threads run in daemon mode. This will allow the framework to
be cached and saves a lot of CPU cycles. However if you edit the code in _ANY_ way you will need
to restart these daemons (or better: stop -> code change -> start) to prevent jobs from failing!



#### MTA Delivery

##### Postfix
Configure delivery using transport maps

> make sure 'isp.local' is in your local domains


Create a file /etc/postfix/transport:
```bash
echo "notifier@isp.local notifier:" >> /etc/postfix/transport
postmap /etc/postfix/transport
```

Set the transport map in the configuration:
```bash
postconf -e transport_maps=hash:/etc/postfix/transport
```

/etc/aliases:
```bash
echo "notifier: notifier.isp.local" >> /etc/aliases
newaliasses
```

Add this to /etc/postfix/master.cf:
```bash
notifier  unix  -       n       n       -       -       pipe
 flags=Rq user=abuseio argv=/usr/bin/php -q /opt/abuseio/artisan --env=production receive:email

```

Restart postfix:
```bash
/etc/init.d/postfix restart
```


#### Webserver

##### Apache httpd
Setting up a simple virtualhost for AbuseIO.
> It is recommended to setup a virtualhost with SSL enabled.

Enable modules:
```bash
a2enmod rewrite
a2enmod headers
```

Create a file /etc/apache2/sites-available/abuseio.conf containing:
```
<VirtualHost _default_:80>
  ServerAdmin webmaster@localhost
  DocumentRoot /opt/abuseio/public

  ErrorLog ${APACHE_LOG_DIR}/error.log
  CustomLog ${APACHE_LOG_DIR}/access.log combined

  Header always add Strict-Transport-Security "max-age=15552000; includeSubDomains"
  Header always add X-Content-Type-Options "nosniff"
  Header always add X-Frame-Options "SAMEORIGIN"
  Header always add X-XSS-Protection "1; mode=block"

  <Directory /opt/abuseio/public/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted 
  </Directory>
</VirtualHost>
```

```bash
a2ensite abuseio
service apache2 reload
```

##### Nginx
This is an example configuration for AbuseIO via Nginx with php fpm. Change it to suit your own setup.  
Make sure the php-fpm processes run as user abuseio.

Create a file /etc/nginx/sites-available/abuseio containing:
```
server {
  listen 80;
  server_name abuseio.myserver.tld;
  root /opt/abuseio/public;
  index index.php;
 
  location ~ \.php$ {
    try_files $uri =404;
    fastcgi_pass unix:/var/run/fpm_abuseio.socket;
    fastcgi_index index.php;
    include fastcgi_params;
  }

  location / {
    try_files $uri $uri/ /index.php?q=$uri&$args;
  }
}
```
```bash
ln -s /etc/nginx/sites-available/abuseio /etc/nginx/sites-enabled/001-abuseio
service nginx reload
```


#### Database setup

##### MySQL
Create a database and a user with permissions. This example will use the local database server.

```bash
mysqladmin -p create abuseio
mysql -p -Be "CREATE USER 'abuseio'@'localhost' IDENTIFIED BY '<password>'"
mysql -p -Be "GRANT ALL on abuseio.* to 'abuseio'@'localhost'"
```



<a name="install_abuseio"></a>
### Installation (as abuseio)
All these things should be done as user 'abuseio', from within the folder /opt/abuseio.

#### Configuration

The .env file contains your base configuration and must be set correctly because you set the application
configuration. An example of the file:

```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=xxx
APP_ID=xxx

DB_DRIVER=mysql
DB_HOST=localhost
DB_DATABASE=abuseio
DB_USERNAME=abuseio
DB_PASSWORD=<password>

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=database
```

##### Initializing the database
```bash
cd /opt/abuseio
php artisan migrate
```

If you want some demo data to play with, you should run the following commands:
```bash
php artisan db:seed
extra/notifier-samples/runall-noqueue
```

##### Creating an admin user for the GUI

By default no accounts are created and you will need to create accounts with the needed permissions on the console. The '1' in the account:create is the number of the brand (1: default brand) You may change this to another value if you have a different brand that you want to attach to this account.

```
cd /opt/abuseio
php artisan account:create <name> 1
php artisan user:create --email admin@isp.local
php artisan role:assign --role admin --user admin@isp.local
```

The user:create command also access items like --password, however if not selected a password will be generated and default settings will be used

##### Cronjobs
Add a crontab for the user abuseio.  
This scheduler job needs to run every minute and will be kicking off internal jobs at the configured intervals from the main configuration. Example:


Run: `crontab -e -u abuseio`
```
* * * * * php /opt/abuseio/artisan schedule:run >> /dev/null 2>&1
```


<a name="resolving"></a>
### Setup local resolving

Some parsers produce high amounts of DNS queries, so your better off using a local resolve (e.g. bind)
in the above install example bind is installed and you only need to update your /etc/resolv.conf (or
with newer ubuntu versions the /etc/network/interfaces) to use 127.0.0.1 as the FIRST resolver, but make
sure you leave a 2nd or 3rd with your 'normal' resolvers.

