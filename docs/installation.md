# Requirements

PHP 5.x or better
MTA that can redirect into pipes (e.g. Exim or Postfix)
Apache 2.x or better
Database backend (mysql, postgres, etc)
Beanstalk Queueing server
Bind or pDNS-recursor

for ubuntu

```bash
apt-get install php5 mysql-server php5-myssql beanstalkd apache2 apache2-utils postfix supervisor bind9 php-pear php5-dev php5-mcrypt git
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
pecl install mailparse (note: mbstring should be installed on linux systems, if not add mbstring to the install command)
echo "extension=mailparse.so" > /etc/php5/mods-available/mailparse.ini
php5enmod mailparse
php5enmod mcrypt
```

## Create local user 

```bash
adduser abuseio
```

## checkout git

Please note that a composer update is required to install all other required packages and dependancies!

```bash using Composer
cd /opt
composer create-project abuseio/abuseio
or composer create-project -s dev abuseio/abuseio if you want latest development release
```

```bash using GIT:
cd /opt
git clone https://github.com/AbuseIO/AbuseIO.git abuseio
cd /opt/abuseio
composer update
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

## Creating MTA delivery

To test your mail route, send a e-mail to notifier@your-MTA-domain.lan and if you want to use the CLI you can directly
send a EML file into the parser using:

```bash
cat file.eml | /usr/bin/php -q /opt/abuseio/artisan --env=production email:receive
```

Please note that using the 'cat' option might give you a difference with email bodies, for example with line 
termination you'd see a \r\n instead of \n or vice versa. Once you have tested your parser by using the cat method
always use the MTA address to validate your work!

### Postfix
 
```bash
echo 'notifier: | "| /usr/bin/php -q /opt/abuseio/artisan --env=production email:receive"' >> /etc/aliasses
newaliasses
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
htpasswd -b -c /opt/abuseio/htpasswd admin password
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
                # Dont forget to protect /admin with an IP/User ACL!

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

                <Location /admin>
                    AuthUserFile /opt/abuseio/htpasswd
                    AuthName "Password Protected"
                    AuthType Basic
                    require valid-user
                </Location>

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
composer update
```

## Setting up configuration

Create the file /opt/abuseio/.env with the following hints:

```bash
APP_ENV=production (change to development if needed)
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
php artisan db:seed < run this only if you want demo material in your installation.
```


