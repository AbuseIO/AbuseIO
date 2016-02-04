# What is AbuseIO?

It is a toolkit to receive, process, correlate and notify end-users about abuse 
reports received by network operators, typically hosting and access providers. 
AbuseIO's purpose is to consolidate efforts by various companies and individuals 
to automate and improve the abuse handling process.

We already have support for the feeds from ShadowServer, SpamCop, Netcraft, 
Google Safe Browsing, IP Echelon, C-SIRT, Project Honey Pot, Abuse-IX and many 
more! Our goal is to support any known feed that provides abuse information.

## AbuseIO's main features:

 * Receive (through a postfix handler) abuse messages and automaticaly parse them into abuse cases  
 * Combine abuse messages that already have an open case in order to reduce the amount of noise  
 * Classify each type of abuse and create actions on specific cases  
 * Create locally-defined customers and/or netblocks or easily integrate your own IPAM system to resolve IP addresses to customers  
 * Set automatic (re)notifications per case or customer  
 * Allow customers to reply, close or add notes to cases - keeping them organized  
 * Link customers to a self help portal in case they need more clues  
 * Works with IPv4 and IPv6 addresses  

More details and documentation can be found on our [Project Website](https://abuse.io).

## Getting started

See [INSTALL](INSTALL.md) for details.

## Getting support

You can find us on the Freenode IRC network in #abuseio

## Note on Patches/Pull Requests

 * Fork the project.  
 * Make your feature addition or bug fix.  
 * Add tests for it. This is important so I don't break it in a future version unintentionally.  
 * Send a pull request.   

## Code of Conduct

Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project you agree to abide by its terms.

# Copyright

Copyright (c) 2015 AbuseIO. See [LICENSE](LICENSE) for details.
