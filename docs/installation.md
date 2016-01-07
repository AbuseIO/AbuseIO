# Requirements

PHP 5.5.9 or better
MTA that can redirect into pipes (e.g. Exim or Postfix)
Apache 2.x or better
Database backend (mysql, postgres, etc)
Bind or pDNS-recursor

for ubuntu

```bash
apt-get install php5 mysql-server php5-mysql apache2 apache2-utils postfix supervisor bind9 php-pear php5-dev php5-mcrypt git
```

# Installation (as root)

## Setup local resolving

Some parsers produce high amounts of DNS queries, so your better off using a local resolve (e.g. bind)
in the above install example bind is installed and you only need to update your /etc/resolv.conf (or
with newer ubuntu versions the /etc/network/interfaces) to use 127.0.0.1 as the FIRST resolver, but make
sure you leave a 2nd or 3rd with your 'normal' resolvers.

## Install global composer 

```bash
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

## Install global dependencies

```bash
pecl install mailparse-2.1.6 
(note: mbstring should be installed on linux systems, if not add mbstring to the install command)
(note: if pecl fails, try pecl install -Z mailparse-2.1.6
echo "extension=mailparse.so" > /etc/php5/mods-available/mailparse.ini
php5enmod mailparse
php5enmod mcrypt
mkdir /var/log/abuseio
chown syslog:adm /var/log/abuseio
```

## Create local user 

```bash
adduser abuseio
```

and add your apache user (www-data) and MTA user (postfix) to the abuseio group

```
addgroup abuseio abuseio
addgroup postfix abuseio
addgroup www-data abuseio
```

NOTE: You will need to restart apache and postfix in this example to effecticly add these groups to the process!

## checkout git

Please note that a composer install is required to install all other required packages and dependancies! Do _NOT_
run the composer update, unless you are a developer.

using Composer:
```bash
cd /opt
composer create-project abuseio/abuseio
or composer create-project -s dev abuseio/abuseio if you want latest development release
```

using GIT:
```bash
cd /opt
git clone https://github.com/AbuseIO/AbuseIO.git abuseio
cd /opt/abuseio
composer update
```

## fix permissions

```bash
cd /opt
chown -R abuseio:abuseio abuseio
chmod -R 770 abuseio/storage
chmod 770 abuseio/bootstrap/cache
```

## Setup supervisor, logrotate, rsyslog:

For easy access you will find these examples in the extra/etc/ directory to be used with 

rsync -vr /opt/abuseio/extra/etc/ /etc/

then:

```bash
supervisorctl reread
/etc/init.d/supervisor restart
service rsyslog restart
```

Importent: The supervisord worker threads run in daemon mode. This will allow the framework to
be cached and saves a lot of CPU cycles. However if you edit the code in _ANY_ way you will need
to restart these daemons (or better: stop -> code change -> start) to prevent jobs from failing!

## Creating MTA delivery

To test your mail route, send a e-mail to notifier@your-MTA-domain.lan and if you want to use the CLI you can directly
send a EML file into the parser using:

```bash
cat file.eml | /usr/bin/php -q /opt/abuseio/artisan --env=production receive:email
```

Please note that using the 'cat' option might give you a difference with email bodies, for example with line 
termination you'd see a \r\n instead of \n or vice versa. Once you have tested your parser by using the cat method
always use the MTA address to validate your work!

### Postfix

Configure delivery using transport maps

/etc/postfix/main.cf:
```bash
postconf -e transport_maps = hash:/etc/postfix/transport
```

/etc/postfix/transport:
```bash
echo "notifier@your-MTA-domain.lan	:notifier" >> /etc/postfix/transport
postmap /etc/postfix/transport
```

/etc/postfix/master.cf:
```bash
notifier   unix  -       n       n       -       -       pipe
    flags=Rq user=abuseio argv=/usr/bin/php -q /opt/abuseio/artisan --env=production receive:email
```


## Configuring Webserver
You should be able to visit the website at URI /admin/ with a document root at /opt/abuseio/public/

### Apache httpd
Don't forget: It's highly recommended to add a .htaccess and secure with both IP as ACL filters!

Enable options and place your SSL cert into /etc/apache2/ssl/:
```
a2enmod ssl
a2enmod rewrite
a2enmod headers
mkdir /etc/apache2/ssl
```

create config /etc/apache2/sites-available/abuseio.conf
```
<VirtualHost _default_:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /opt/abuseio/public
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R=301,L]
</VirtualHost>
<IfModule mod_ssl.c>
        <VirtualHost _default_:443>
                ServerAdmin webmaster@localhost
                DocumentRoot /opt/abuseio/public

                ErrorLog ${APACHE_LOG_DIR}/error.log
                CustomLog ${APACHE_LOG_DIR}/access.log combined

                Header always add Strict-Transport-Security "max-age=15552000; includeSubDomains"
                Header always add X-Content-Type-Options "nosniff"
                Header always add X-Frame-Options "SAMEORIGIN"
                Header always add X-XSS-Protection "1; mode=block"

                SSLEngine On

                SSLProtocol ALL -SSLv2 -SSLv3
                SSLCipherSuite ECDHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA256:ECDHE-RSA-AES256-SHA:ECDHE-RSA-AES128-SHA:DHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-SHA256:DHE-RSA-AES128-SHA256:DHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA:AES256-GCM-SHA384:AES128-GCM-SHA256:AES256-SHA256:AES128-SHA256:AES256-SHA:AES128-SHA:DES-CBC3-SHA
                SSLHonorCipherOrder on

                SSLCertificateFile       /etc/apache2/ssl/yourisp.local.crt
                SSLCertificateKeyFile    /etc/apache2/ssl/yourisp.local.key
                SSLCACertificateFile     /etc/apache2/ssl/yourisp.local.int

                <FilesMatch "\.(cgi|shtml|phtml|php)$">
                                SSLOptions +StdEnvVars
                </FilesMatch>
                <Directory /usr/lib/cgi-bin>
                                SSLOptions +StdEnvVars
                </Directory>

                <Directory /opt/abuseio/public/>
                    Options Indexes FollowSymLinks
                    AllowOverride All
                    Require all granted 
                </Directory>


        </VirtualHost>
</IfModule>

```

### Nginx
This is an example configuration for running AbuseIO via Nginx with php fpm. Change it to suit your own setup.
The important line is the "try_files" line which emulates the .htaccess behaviour.
##### /etc/nginx/sites-available/abuseio
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

```
ln -s /etc/nginx/sites-available/abuseio /etc/nginx/sites-enabled/001-abuseio
service nginx reload
```

# Installation (as abuseio)

## Install dependencies

```bash
cd /opt/abuseio
composer install
```

## Setting up configuration

Create the file /opt/abuseio/.env with the following hints:

```bash
APP_ENV=production (change to development if needed)
APP_DEBUG=false (change to true if needed)
APP_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

DB_DRIVER=mysql
DB_HOST=localhost
DB_DATABASE=abuseio
DB_USERNAME=username
DB_PASSWORD=password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=database
```

## Installing and seeding database

```bash
cd /opt/abuseio
php artisan migrate:install
php artisan migrate
php artisan key:generate

php artisan db:seed < run this only if you want demo material in your installation, like users, tickets, etc
```

## Creating an admin user for the GUI

By default no accounts are installed and you will need to create accounts with the needed permissions on the console:

```
cd /opt/abuseio
php artisan user:create --email admin@isp.local
php artisan role:assign --role admin --user admin@isp.local
```

The user:create command also access items like --password, however if not selected a password will be generated and default settings will be used

## Add cron job

add a crontab for the user abuseio. This schedular job needs to run every minute and will be kicking off internal jobs
at the configured intervals from the main configuration. Example:

```
crontab -e -u abuseio
* * * * * php /opt/abuseio/artisan schedule:run >> /dev/null 2>&1
```