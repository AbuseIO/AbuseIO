# This is a development branch

Do NOT use this for any kind of production

# Installation

Install composer:

cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer


checkout git:

cd /opt
git clone -b AbuseIO-4.0 git@github.com:AbuseIO/AbuseIO.git abuseio
chown -R www-data:www-data storage

Install dependencies:

composer install
pecl install mailparse (note: mbstring should be installed on linux systems, if not add mbstring to the install command)
echo "extension=mailparse.so" > /etc/php5/mods-available/1000-mailparse.ini
ln -s /etc/php5/mods-available/1000-mailparse.ini /etc/php5/cli/conf.d/1000-mailparse.ini
ln -s /etc/php5/mods-available/1000-mailparse.ini /etc/php5/apache2/conf.d/1000-mailparse.ini

Setting up configuration:

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


Installing and seeding database:

php artisan migrate:install
php artisan migrate
php artisan db:seed

You should be able to visit the website at URI /admin/ with a document root at /opt/abuseio/public/