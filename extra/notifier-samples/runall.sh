for file in `find /opt/abuseio/extra/notifier-samples/ -type f | grep eml`
  do
    echo "pushing $file into queue"
    cat $file | /usr/bin/php -q /opt/abuseio/artisan email:receive --debug=true
done
