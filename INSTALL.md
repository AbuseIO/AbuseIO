# AbuseIO

## Getting Started

This getting started document will guide you quickly through the installation and setup. A more
detailed instruction document and documentation can be found on our [website](https://abuse.io)

## Requirements

A simple virtual machine (1 core, 1 GB ram) will suffice for normal operations. If you intend to
use the RBL scanner in combination with rbldnsd zonefiles then 4 GB ram is recommended.

### Dependencies

At this point we assume you have a working web- and mailserver. The configuration of these components
is outside the scope of this document.

The AbuseIO scripts can be placed anywhere on your system, but if you cannot choose then /opt/abuseio/
would be a good place to start. Point your webserver to APP/htdocs.

Create a database and import the APP/sql/abuseio.sql schema.

Some additional packages are required:

#### Ubuntu

```
apt-get install php-mail-mimedecode php5-cli php5-curl php5-mysql apache2 php5 postfix mysql-server
```

#### CentOS

```
yum install php-cli php-mysql php-pear-Mail-mimeDecode
```

### Required configuration

 - Setup your DNS:

    DNS configuration is important, but it allows you access to the three different hosted vhosts within
    AbuseIO. In addition, placing an MX record is mostly required for AbuseIO to receive abuse events from 
    remote feeds.

    For example with the following DNS setup:

    ```
    abuseio.isp.tld IN A 10.0.0.1i  
    mx.isp.tld IN A 10.0.0.1  
    abuseio.isp.tld IN MX 100 mx.isp.tld.  
    admin.abuseio.isp.tld IN CNAME abuseio.isp.tld.  
    ash.abuseio.isp.tld IN CNAME abuseio.isp.tld.  
    rpc.abuseio.isp.tld IN CNAME abuseio.isp.tld.  
    ```

    Your servername would be abuseio.isp.tld under IP 10.0.0.1. The MX record is important! It will allow you
    to forward email directly into AbuseIO for parsing. The e-mail domain would be @abuseio.isp.tld.


 - Setup your apache configuration

    admin.abuseio.isp.tld -> /opt/abuseio/www/admin (pw / ip acl protected!)  
    ash.abuseio.isp.tld   -> /opt/abuseio/www/ash  
    rpc.abuseio.isp.tld   -> /opt/abuseio/www/rpc  


 - Set permissions:
 
    Preferably create a new user (e.g. abuseio) and set ownership with mod 755 to the entire installation base. The
    http server (e.g. apache) should only have read permissions to the install base

    ```
    chmod -R 777 tmp  
    chmod -R 664 archive 
    ```
    
    please make sure that the archive folder ownership is correctly set. The group should be set to 
    your MTA group, so that your MTA can actually write there.


 - Set configuration

    - Copy APP/etc/settings.conf.example to APP/etc/settings.conf and modify the settings.
    - Copy APP/etc/mail.template.example to APP/etc/mail.template and modify the e-mail template for end-users


 - Configure custom modules

    - To enrich reports with customer data, copy APP/lib/custom/find_customer.php.example to APP/lib/custom/find_customer.php and implement a hook to fetch customer information.
    - To use the notifier hook to report events on IRC or Quarantine a host, copy APP/lib/custom/notifier.example to APP/lib/custom/notifier.php and implement a hook for custom notifications


 - Create cronjobs 

    example:

        # m h dom mon dow user  command
        10 * * * *     root    /opt/abuseio/bin/housekeeper
        */15 * * * *   root    /opt/abuseio/bin/notifier

    About these cronjobs:

    - housekeeper does regular maintainance tasks as well as using collectors (if enabled) to fetch information that isn't sent by mail

    - notifier is a script that sends out notifications to customers (if enabled)


### Optional configuration

 - AbuseIO logs to syslog (local.1 facility), so you might want to review your syslog configuration to log all AbuseIO messages to a separate file.

 - If you have a Microsoft SNDS account, enable "Automated Data Access" at https://postmaster.live.com/snds/auto.aspx and configure your key in APP/etc/settings.conf

### Abuse mail

AbuseIO can receive and parse abuse mail. When AbuseIO encounters an unknown sender or a parser failure, it will log and abort with a EX_TEMPFAIL return code, indicating to your MTA
that the mail could not be delivered. Sane MTAs will attempt to deliver the mail later, allowing you to fix the problem, so the mail can be processed again.

Hooking up your abuse mail can be implemented in various ways:

#### Option 1: Hook up your MTA directly to AbuseIO (Best way)

Make sure you configured the DNS correctly. The delivery address for abuse mails to be parsed would be notifier@isp.tld

Simply add the following line to your /etc/aliases file to enable email delivery directly to AbuseIO:

    notifier: |"/path/to/libexec/mda"

For example ``` notifier: | "php -q /opt/abuseio/libexec/mda```

(Do not forget to run the newaliases command to inform your MTA that the aliases file has been updated.)

After that you will need to forward either abuse@isp.tld to abuse@abuseio.isp.tld so that incoming e-mails 
are redirected to AbuseIO. In addition a lot of feeds have the option to deliver on a custom address. Using 
addresses like spamcop-abuse@isp.tld and forwarding them to abuse@abuseio.isp.tld will give you more control 
to enable or disable individual feeds, for example:

    alias spamcop@isp.tld deliver to abuse@isp.tld AND notifier@abusio.isp.tld  
    alias shadowserver@isp.tld deliver to abuse@isp.tld AND notifier@abusio.isp.tld  
    alias netcraft@isp.tld deliver to abuse@isp.tld AND notifier@abusio.isp.tld  
    alias csirt@isp.tld deliver to abuse@isp.tld AND notifier@abusio.isp.tld  
    alias spamexperts@isp.tld deliver to abuse@isp.tld AND notifier@abusio.isp.tld  


#### Option 2: Monitor a remote mailbox using fetchmail

Install the fetchmail package using your package manager and configure it by placing the following contents 
in your ~/.fetchmailrc:

    poll myserver.com proto imap
    user "account"
    pass "password"
    keep
    mda "/path/to/libexec/mda"

AbuseIO will process all (new) mails from this mailbox. If parsing succeeds fetchmail will mark the mail as 
read. If the mail cannot be parsed by AbuseIO, fetchmail will not touch the email. If you want to re-process 
an abuse report, simply mark the abuse email as (new) and run fetchmail again.

### Using ASH information texts

We include a default set of information texts per class in APP/www/ash/infotext/defaults/ split up in multiple
languages. These are shown in combination with a report of that named class.

If you want your own text with a class you can create the class html in APP/www/ash/infotext/ with the same name.
files in this folder are preferred over the defaults. You for conviniance we included a little template.html
file to get you started.

## Note on Patches/Pull Requests

 * Fork the project.
 * Make your feature addition or bug fix.
 * Add tests for it. This is important so we dont break it in a future version unintentionally.
 * Send a pull request. 

## Code of Conduct

Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project you agree to abide by its terms.

# Copyright

Copyright (c) 2015 AbuseIO. See [LICENSE](LICENSE) for details.
