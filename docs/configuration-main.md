# Configuration settings

After the installation you are required to set a few basic settings to assure all operations will run as intended.
All options are set with defaults in the ./app/config/main.php file which should never be changed. In the config
folder you also have tree subfolders called production, development and testing. You can place your own main.php in 
each of directories which represents the running environment so you can use a different config for testing and
production. The items you will not define in your custom config file will use the defaults from the 
./app/config/main.php file

## interface

### language = 'XX'

Here you can define the default language to be used if there is no specific setting for a language. If a users logs
in for the first time, this language will be selected. However when the user changes his language setting that will be
persistant for that user. Unauthenticated pages (like ASH) will also use this as a default language.

### navigation = Array(items)

Legacy option, will be removed soon.

## emailparser

### fallback_mail = 'admin@isp.local'
### store_mail = true/false
### store_evidence = true/false
### remove_evidence = 'xx yyyyyy'
### notify_on_warnings = true/false

## reports

### match_period = 'xx yyyyyy'
### close_after = 'xx yyyyyy'
### resolvable_only = true/false
### resolvable_netblocks = Array(items)
        
## notes

### enabled = true/false
### deletable = true/false
### show_abusedesk_names = true/false

Defined wither or not the name (first/last) will be shown next the the AbuseDesk name. This would be handle so a 
contact would be able to refer to a specific case owner.

## notifications

### info_interval = 'xx yyyyyy'
### abuse_interval = 'xx yyyyyy'
### min_lastseen = 'xx yyyyyy'
### from_address = 'abuse@isp.local'
### from_name = 'ISP Abusedesk'
### bcc_enabled = true/false
### bcc_address = 'management@isp.local'

## ash

### url = 'https://abuseio.isp.local/ash/'

## external

### findcontact = Array(items)

### notifications = Array(items)

