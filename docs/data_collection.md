# How incoming data is received

The system has several methods of receiving abuse events:

1. By e-mail directly, using the MDA delivery (pipe) directly into the Artisan handler
2. By e-mail indirectly, via logging into a mailbox and collecting the e-mails that can be parsed
3. By RPC push notifications
4. By collecting data from remote hosts, e.g. HTTP(s) or RSS subscriptions
5. By directly inputting data from the CLI, which is handy if you have your own local scantools

Once the data has been received the datafile will be saved onto the archive and a processor job is queued to change
this raw data into tickets and events we can then use for notifications. The processor job will be calling each step
in the process to end up with a parsed data set in the database.

# The Processor job

## Disecting the e-mail / raw data

In most cases you will receive an e-mail or raw data. In the first stage we convert the data into an object, for example
a parsedEmail object which allows easy access to specific areas of that object like headers or RFC822 evidence.

## Calling the right handler for the job

Since every notifier and sometimes even individual feeds from the same notifier have different formats we have created
specific handlers, mostly parsers, to convert the data object into events. A received object might contain a single
event (e.g. a SPAM feedback loop) or multiple events (e.g.: a large combined CSV dataset).

Each handler (parser, collector, or otherwise) is a package on its own. With releases we will provide a full set of
all available production ready handlers, but you will be able to add, remove or update individual packages.

Based on all the available parser configurations the system will determine which handler can be used to handle the dataset. This decision is made by two methods:

- By sender address mapping, using the FROM address we know in most cases who the notifier and their feed is
- By body mapping, using a regex to match an area of the raw message

Once a handler (parser) has been selected it will be called to parse the dataset. At this stage we will create an 
event object for each event found in the database which contains:

- **timestamp**: this is the timestamp for when the notifier has seen this event, not when we received the data
- **source**: the name of the notifier/parser name that handled the event
- **information**: a multilayered array of data regarding the event
- **class**: the internal classification, using a preset list to be used for showing information texts
- **type**: if the event is informational, abuse or escalated. This triggers different kinds of notifications
- **ip address**: the ip address of the events
- **domain name**: optional: the domainname regarding the event
- **uri**: optional: the URI where the event was found/directed to

## Validations

If you are writing your own parser or collector you will notice that you hardly see any validation being done on that
level. That is because we handle everything else besides parsing within the main framework. This will allow us to make
sure a parser will provide data we can actually use. In short: A parser should only disect the information blob into
event objects.

Since each event object should be exactly in the same format we will save you a lot of work on custom parsers' 
validations. Once a parser has completed it would only return the events in a standard format. After that the Validator 
is called to go through each of the event objects and validate each part of the event. For example the Validator 
will check whether the field 'ip' actually contains a valid IPv4 or IPv6 address.

## Saving evidence data

When the data has successfully been validated we can now write the evidence into saving. This evidence object is 
a link containing some basic information (like sender) and the physical location of the file where it was stored. The
ID of the evidence is linked to each of the events that was generated from this evidence.

We will not change anything from the raw dataset and write this unchanged onto the filesystem for reference. This will
allow each access to the evidence as well as forcing rescans if needed.

## Saving tickets and events

The system will see if there is an existing case. It uses a query in the ticket system based on IP, domain, class,
type and IP owner. If this combination of items already has an existing ticket then the event will be added onto this
existing ticket. In other cases it would create a new ticket with the event attached to it.

Here we will split up the event into two sections:

- **ticket data**: static data, like the IP address, class, etc that will not change
- **event data**: dynamic data, that mainly contains the data blob of information from the notifier

After splitting these sections we will enrich the data with contact information for the IP owner and/or domain owner.
This data will be added onto the ticket data and be static. You want to save the data, because if you use DHCP then the
owner might be different when you ask it later for the same IP, while the abuse we regarding another owner.

## Sending out notifications

Note about domains: Since the IP is the physical location of where the abuse is located we call this leading for
notifications. If there is a domain name referred onto the ticket we will look up the Domain owner to notify
as well. The domain owner is just an administrational item hence not included in the lookup.
This will result in two types of behaviour: If the IP owner changes (or domain, class, etc) the system will consider
it a new incident and creates a new ticket. If the Domain owner changes the existing ticket will remove the old domain
contact and replaces it with the new contact. Changing the Domain contact will also result in changing the access
token to ASH and only the 'new' owner will have access to the ticket.

There are 3 kinds of notifications that can be sent out either as a single notification or in conjunction with each
other. These notifications are: 

- E-Mail message: An e-mail with a simple text referring the contact to ASH for more information
- RPC message: a IOdef formatted message for contacts that automate their abuse handling (e.g. resellers)
- External message: a customized notification that is called (e.g. IRC notification or login onto router and 
create a blackhole for the IP).

Based on the ticket type (Info, Abuse, Escalation) the notifications have configurable intervals. By default we
send abuse notifications every 15 minutes and informational notifications every 90 days.

## Data collection errors

Parsers that only set a warning counter that increase when the parsers has hit a snag. Additional warnings
are automaticly added by parser-common for events like where a parser did not return any event at all.

By default the handler is set to treat these warnings as errors and will not even try to continue validating
or saving the found events. Instead the original EML file is added onto an e-mail which is sent to the admin
to investigate. In most cases this is the result of a notifier changing the format or misconfiguration.

If you want to continue and try to save the stuff you did find you can change the setting parser_warnings_are_errors
to false.

## Retrying data collection

If a parser had problems and only partially saves the events found in the data set, you can simply just retry
the parsing by reintroducing the e-mail onto the system (either by bouncing the EML or using CLI tools) so the
parser will try to handle the e-mail again.

You will not have to worry about getting duplicates, as there is a filter on saving events that are an exact match.
Only is there is actually over data (e.g. timestamp, or a infoblob value) then the event will be saved.

# Local and remote contact data

The system comes with a built-in database for registering IP owners, netblocks and domains. This local database always
has preference above remote data calls. If you have an external database to collect this data from you can leave these
sections empty or use them as an override of your remote data call.

For each of the items (contacts, netblocks and domain) you can specify your own backend(s). For example you could have
NIPAP for your IP administration which contains the customer ID's and SAP Accounting that holds your customers' contact
information. In this case you can use them both to fill in the contact data.

The integration of remote contact data is explained in its own chapter

# Changing the default parser/collect configuration

Each parser has its own dedicated configuration. This can be divided into four sections. Lets take a look at the
parser for shadowserver which can be found in ./vendor/abuseio/parser-shadowserver/.

This directly contains the parser in the /src/ directory and the configuration in the /config/ directory. Inside the 
config directory you will find the Shadowserver.php containing the default configuration (which you should never edit)
and three subfolders called production, development and testing. You can place your own Shadowserver.php in each of 
directories which represents the running environment so you can use a different config for testing and production.

The contents of the config file is simply a single PHP array with the configurable items. You will not have to recreate
the entire array, but only need to create the same array keys with values you want to change. The configuration will
be merged and your custom configuration will be overlaid onto the existing array.

By default we have created a lot of options you can change and you will be able to change the notifier information
and the per-feed settings from that notifier without having to change the parser code itself.