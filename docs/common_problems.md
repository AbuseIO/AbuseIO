# Common AbuseIO problems / FAQ

## 1. Services not running (properly)

Symptoms: 

- You will get incoming e-mails, their logged as being received but the e-mail is not processed 
- AND/OR You are missing data from tickets you are sure have been mailed onto the system
- AND/OR You will see a lot of jobs when running `php artisan queue:list`
- AND/OR You get a alert of jobs being stuck in the queue

Possible cause:

Services not running. Most likely after installation the service was asked to start, however
because the database has not been fully installed yet the daemon did not start.

Once an e-mail has been received it is put into a queue to handle and the MTA part has been completed. The MTA can 
return an 200 OK message to the sender. If the service queue daemons are not running, no jobs are picked up from
the queue and remain there until worker process has been started.

Solution:

Restart the services (currently 3) or, if you're using supervisord, the entire supervisord daemon, which is safe to do if this is system is dedicated to AbuseIO. After restarting it you will see in the /var/log/abuseio/queue* files the workers to pick up work and start handling the data from the received e-mails.

## 2. Call to undefined function mailparse_msg_create() in ../src/Parser.php on line 128

The module is installed and enabled, but I stil lget this error.

Possible cause:
Postfix is not allowed to read mailparse.ini so it will not enable the module. Some user ran into this problem because of OS hardening.

Solution:

Make sure the mailparse.ini file is readable by the postfix user.
