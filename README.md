# AbuseIO

## Getting support

You can find us on the FreeNODE IRC network in #abuseio

check [IRC channel Guidelines and F.A.Q.](https://abuse.io/abuseio/abuseio/wikis/ircandfaq) for details

## Contributions

## Development

## Getting Started

### Dependencies

At this point we assume you have a working web- and mailserver. The configuration of these components
is outside the scope of this document.

The AbuseIO scripts can be placed anywhere on your system. Point your webserver to APP/htdocs.

Create a database and import the APP/sql/abuseio.sql schema.

Some additional packages are required:

#### Ubuntu

* apt-get install php-mail-mimedecode php5-cli php5-curl php5-mysql

#### CentOS

* yum install php-cli php-mysql php-pear-Mail-mimeDecode

### Configuration

 - Copy APP/etc/settings.conf.example to APP/etc/settings.conf and modify the settings.
 - To enrich reports with customer data, copy APP/lib/custom/find_customer.php.example to APP/lib/custom/find_customer.php and implement a hook to fetch customer information.

Optional:

 - Create daily cronjobs for APP/bin/fetch_reports (fetches and parses SNDS reports) and APP/bin/rbl_scanner (scans RBL for listing of IPs referenced in recent reports)
 - AbuseIO logs to syslog (local.1 facility), so you might want to review your syslog configuration to log all AbuseIO messages to a separate file.
 - If you have a Microsoft SNDS account, enable "Automated Data Access" at https://postmaster.live.com/snds/auto.aspx and configure your key in APP/etc/settings.conf

### Abuse mail

AbuseIO can receive and parse abuse mail. When AbuseIO encounters an unknown sender or a parser failure, it will log and abort with a EX_TEMPFAIL return code, indicating to your MTA
that the mail could not be delivered. Sane MTAs will attempt to deliver the mail later, allowing you to fix the problem, so the mail can be processed again.

Hooking up your abuse mail can be implemented in various ways:

#### Option 1: Monitor a remote mailbox using fetchmail

Install the fetchmail package using your package manager and configure it by placing the following contents in your ~/.fetchmailrc:

poll myserver.com proto imap
    user "account"
    pass "password"
    keep
    mda "/path/to/libexec/mda"

AbuseIO will process all (new) mails from this mailbox. If parsing succeeds fetchmail will mark the mail as read. If the mail cannot be parsed by AbuseIO, fetchmail will not touch the email.
If you want to re-process an abuse report, simply mark the abuse email as (new) and run fetchmail again.

#### Option 2: Hook up your MTA directly to AbuseIO

Simply add the following line to your /etc/aliases file to enable email delivery directly to AbuseIO:

abuse: |"/path/to/libexec/mda"

(Do not forget to run the newaliases command to inform your MTA that the aliases file has been updated.)

## Note on Patches/Pull Requests

 * Fork the project.
 * Make your feature addition or bug fix.
 * Add tests for it. This is important so I don't break it in a future version unintentionally.
 * Send a pull request. 

## Code of Conduct

Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project you agree to abide by its terms.

# Copyright

Copyright (c) 2015 AbuseIO. See [LICENSE](https://abuse.io/abuseio/abuseio/blob/master/LICENSE) for details.
