<?php

return [

    /*
     *--------------------------------------------------------------------------
     * Infotext Language Lines
     *--------------------------------------------------------------------------
     *
     * The following language lines are used by the ASH and admin views to
     * display specific event information
     *
     * This page may defer from PSR-2
     *
     */
    'DEFAULT' => [
        'name'        => 'Generiek bericht',
        'description' => 'Deze klassificatie heeft nog informatie.',
    ],

    'BOTNET_CONTROLLER' => [
        'name'        => 'Botnet server',
        'description' => 'Deze klassificatie heeft nog informatie.',
    ],

    'BOTNET_INFECTION' => [
        'name'        => 'Botnet infectie',
        'description' => "
             <h2>What is a 'Botnet infection'?</h2>

            <p>Botnet is a portmanteau derived from the words robot and network. Bot refers
            to a computer program that independently performs automated jobs. Such programs
            have many legitimate uses; search engines, for example, commonly use bots to
            catalogue web sites. Unfortunately, bots can also be programmed to perform
            malicious actions on systems. A botnet is a large group of infected computers
            connected to each other via the internet. Criminals administering the botnet
            make sure that the the programs get installed on as many systems as possible.
            The programs stay under the radar, generally running in the background, and are
            usually difficult for antivirus software to identify. Once a computer is infected,
            it can then become part of the botnet through the exploitation of vulnerabilities
            in software installed on the user's system. There are many avenues for this
            infection, such as visiting a (generally infected) web site, 'drive-by downloads'
            (when malware is downloaded and installed on the system without the user's knowledge),
            and even by simply clicking attachments or links in an e-mail or merely connecting
            peripherals such as USB sticks or external hard drives to the system.</p>

            <h2>Why would this be bad?</h2>

            <p>The IP listed in the report (the system behind it using NAT), has seen
            participating inside the botnet. With your system in communication with the
            botnet you can be 99,9% sure it has been compromised. It's hosting malware
            and is participating in a botnet.</p>

            <p>A botnet can be used to steal your personal data, send
            spam, hack into other computers and launch network attacks. In these examples
            you are the actual source of these attacks!</p>

            <h2>Recommended action</h2>

            <p>This issue needs to be resolved by removing the malicious software. In
            very persistent infections you will need to reinstall the system to get rid
            of the infection.</p>

            <h2>Tips to resolve this matter</h2>

            <p>If your system is a workstation or server:<ul>
            <li>Install including all updates an antivirus software and run a full scan of your computer.
            It's recommended to try several scanners as not every scanner is able to detect a specific
            kind of malware.</li>
            <li>Scan the network for infected files. Don't forget to scan the backups, as well as any
            computers that may have been 'off' for the last little while because of employee vacation,
            etc.</li>
            <li>Users of any system with a botnet infection should change all their passwords as quickly
            as possible. This should include any passwords saved locally and used to access other systems
            and applications (including business applications, access to web site administrator accounts,
            personal use of e-mail and social media, etc.).</li>
            <li>If a computer is infected with malware, there is a significant chance that other malware is
            also present on the system. A clean reinstall will help prevent any other infections from
            having free access to the network.</li>
            </ul></p>

            <p>If your system is a website / hosting system:<ul>
            <li>If you suspect a user account has been compromised, change the password for the account
                                                                                            immediately.</li>
            <li>Scan all hosted websites and temporary directories for suspicious files and remove them.
            Also check your process list for suspicious processes and kill them.</li>
            <li>Check your mail queues for outgoing SPAM and remove them for the queue.</li>
            <li>Install a rootkit and a virus scanner to ensure all malicious files have been removed.</li>
            </ul></p>

            <p>Once all malicious software has been removed make sure the server can't be compromised again
            by installing the latest updates for your operating system, control panel and hosted
            applications, including themes and plugins (i.e. Wordpress). If you do not upgrade them
            directly you will face a re-infection in a very short time!</p>

            <h2>Getting more information</h2>

            <a target'_blank' href='https://www.ncsc.nl/binaries/content/documents/ncsc-en/current-topics/factsheets/release-me-from-a-botnet/1/Release%2Bme%2Bfrom%2Ba%2Bbotnet.pdf'>NCSC factsheet - Release me from a botnet</a><br>

            ",
    ],

    'COMPROMISED_SERVER' => [
        'name'        => 'Gecompromitteerde server',
        'description' => 'Deze klassificatie heeft nog informatie.',
    ],

    'COMPROMISED_WEBSITE' => [
        'name'        => 'Gecompromitteerde website',
        'description' => "
            <h2>What is a 'Compromised website'?</h2>

            A comprimised website is (hacked) content placed on your site without your permission
            as a result of vulnerabilities in your site’s security.

            Malicious hackers are a devious bunch – always looking for new flaws, exploits and
            social engineering tricks that will allow them to compromise a website. With
            this in mind, it comes as no surprise Malicious hackers are a devious bunch – always
            looking for new flaws, exploits and social engineering tricks that will allow them
            to compromise a website. With this in mind, it comes as no surprise that most website
            owners simply don’t know how their sites were compromised.

            <h2>Why would this be bad?</h2>

            <p>When your website is compromised, not only your website contains changes done by hackers
            but it also contains one or more security problems that allowed a hacker to gain access
            to your website in the first place. The compromised website provides a useful platform for
            a range of illicit activities. These activities include: </p>

            <ul>
            <li>Hosting malware – this may take the form of complex scripts that infect any visiting
            PC. Alternatively, well-crafted emails may have convinced a recipient to download a
            malware file that is hosted on the compromised site. In most cases the malware script
            is hidden in a subdirectory.</li>
            <li>Injected content (SQL). When hackers gain access to your website, they might try to
            inject malicious content into existing pages on your site. This often takes the form of
            malicious JavaScript injected directly into the site, or into iframes.</li>
            <li>URL redirect – thousands of compromised sites may perform simple redirects to a
            few master URLs. This is accomplished with a few lines of HTML code hidden in the
            compromised site, forcing the site to act as a 'front door' to the badware. The master
            URLs contain spam product pages or malware.</li>
            <li>Hosting phishing, spam pages, pornography – one or two static pages on the compromised
            site may advertise spam products (pharmaceuticals, replicas, enhancers, etc.), act as
            phishing pages for banks, PayPal, Gmail, etc., or offer explicit (sometimes illegal)
            content</li>
            <li>Vandalism – the aim of the compromise might be to embarrass the site owner or,
            alternatively, to make some political point – generally known as 'hacktivism'. Some
            administrators even reported such vandalism by their competitors. </li>
            <li>Other content or activity – some fairly complex forms of site misuse have
            been recorded. For example a spam-sending script.</li>
            </ul>

            <h2>Recommended action</h2>

            <p>If your site has been hacked or infected with malware, you should act quickly to repair the
            damage. First take your entire website offline first. This is pretty impopulair, however if
            you consider the fact your might be leaking privacy information or infecting the
            systems of your visitors you want to act as quickly as possible.</p>

            <p>After you closed your website you will need to clean up the changed area's of your
            website.</p>

            <h2>Tips how to resolve this matter</h2>

            <p>The safest way to clean the hacked website is to delete it entirely and then upload
            a known clean version of it.</p>

            <ul>
            <li>Look for files that have been changed recently or on times your developers are
            closed and not placing updates on the website</li>
            <li>Look in temporally folders for (executable) scripts</li>
            </ul>

            <p>In addition future risk of compromise can be reduced by following these basic tips: </p>

            <ul>
            <li>Keep software and all plug-ins updated. Whether you run popular content
            management software (e.g., WordPress, Joomla, Blogger) or custom software,
            make sure that software and all third party plug-ins or extensions are updated.
            Remove plug-i ns or other add-ons that aren’t in use</li>
            <li>Use strong, varied passwords. WordPress login credentials, for example,
            should be different from FTP credentials. Never store passwords on your local
            machine.</li>
            <li>Regularly scan your PC for malware and your website for unautherized changes</li>
            <li>Use appropriate file permissions on your web server.</li>
            <li>Research your options and make security a priority when choosing a web hosting
            provider. If you aren’t confident you can protect your site on your own, consider
            using an add-on security service from your hosting provider or a third party website
            security service.</li>
            </ul>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://www.google.com/webmasters/hacked/'>Google's help for webmasters of hacked websites</a><br>
            <a target'_blank' href='http://www.stopbadware.org/'>stopbadware.org has great information, and their forums have a number of helpful and knowledgeable volunteers who may be able to help.</a><br>
            <a target'_blank' href='http://www.antiphishing.org/'>The site antiphishing.org has recommendations on dealing with hacked sites.</a><br>

            ",
    ],

    'DISTRIBUTION_WEBSITE' => [
        'name'        => 'Distributie website',
        'description' => "
            <h2>What is a 'Distribution Website'?</h2>

            <p>A distribution website, is a site that is hosting malware for download.
            These are often download links embedded into malicious doc's or executable's code for example.
            Compromised websites are also highly used here. </p>

            <h2>Why would this be bad?</h2>

            <p>If you are hosting malware on your server. Stop and remove this, this is illegal. This can lead to your site being
            flagged as suspicious by search engines causing accessibility issues for visitors. If a hacker is hosting
            malware on your site, then it's also likely compromised.</p>

            <h2>Recommended action</h2>

            <p>Remove any maliciously hosted files, check for signs of compromise. Clean up any infected files.</p>

            <h2>Tips how to resolve this matter</h2>
            <ul>
            <li>If this is a CMS (WordPress, Drupal, Joomla etc), check the addons/plugins and update where possible.</li>
            <li>If this is a standard website, check for signs of infection or unknown links on web-pages and take the necessary action.</li>
            <li>Once the issue(s) have been resolved, begin to request re-evaluations of your site from search engines.</li>
            </ul>

            <h2>Getting more information</h2>

            <a target'_blank' href='https://support.google.com/webmasters/topic/4598410?hl=en&ref_topic=4596795'>Google Webmaster tools for infected sites</a><br>
            <a target'_blank' href='http://www.bing.com/webmaster/help/malware-re-evaluation-e6982183'>Bing Reevaluation site</a><br>

            ",
    ],

    'FEEDBACK_LOOP' => [
        'name'        => 'Feedback Loop (FBL) bericht',
        'description' => "
                <h2>What is A Feedback Loop?</h2>

                <p>In general, a feedback loop (FBL) or complaint feedback loop is
                an inter-organizational form of feedback, by which an Internet service provider (ISP) forwards
                email complaints originating from their users, back to the senders' organizations. Generally,
                ISPs expect that these transactions are processed as unsubscribe requests and that the sender
                researches the nature of the request, to reduce the incidence of such complaints.</p>

                <p>The most common method for ISPs to receive users' complaints is by placing a report spam button
                on their webmail pages or in their email client. Or, the user may send the email to the ISP
                Postmaster. In rare cases, these feedback loops may not be based on user reports. For example,
                they may be based on automated virus detection, or similar mechanisms.</p>

                <p>Some ISPs remove the email address of its customer before returning the message to the sender,
                for privacy or legal reasons. This means it is important for senders to utilize some method
                other than an email address within a message, in order to identify the recipient.</p>

                <h2>Why Is A Feedback Loop Important?</h2>

                <p>As of May 2008, there were 12 FBLs in place at several of the world's largest ISPs including
                AOL, Hotmail and Yahoo. Feedback loops have become an email industry standard. The data
                returned as part of the feedback loop system is extremely valuable in a couple of ways:</p>

                <p> - The first is for list hygiene: Members who complain through a feedback loop can be
                unsubscribed, thereby reducing future complaints. Some may call this list-washing, but
                it's just common sense. Even if someone previously opted in to receive messages, if the
                user complains, the first thing you should do is cease mailing to that user.</p>

                <p> - The second use of FBLs is to analyze the complaints. There is a wealth of data in who
                complains and what they complain about. Regardless of whether you believe the complaints are
                unfounded, if they complained they were dissatisfied. Smart marketers aim to avoid dissatisfied
                customers or prospective customers.</p>

                <h2>What can i do?</h2>

                <p>Campaigns, subject lines and \"from\" addresses can also be monitored to ensure that campaign
                elements are working well. You can identify areas that need improvement. If a particular mailing,
                list, or list segment produces too many complaints, it bears further investigation. Many
                complaints are caused by a failure to meet expectations. As an example, there may be a high
                complaint rate among new subscribers. This can be caused by subscribers not getting what
                they thought they signed up for or by a long delay between sign-up and the first mailing.</p>

                <p>To further complicate matters, most ISPs have complaint rate thresholds above which your
                messages may be filtered or blocked. Unfortunately, most ISPs don't publish these
                thresholds, which vary by ISP. Monitoring the FBL data will improve your email practices,
                while ensuring complaint rates do not exceed Internet Service Provider (ISP) thresholds.</p>

                <h2>But i am not sending these e-mails</h2>

                <p>If you receive a high number of FBL complaints on messages that you did not send yourself
                you should consider the host being reported on (e.g. the IP) to be comprimised as these e-mails
                did originate from your system. In these cases your advised to shutdown the mailserver and
                clean the system and its mailqueues before taking it online again.</p>

                <h2>What Is Questionable About Feedback Loops?</h2>

                <p>The spam button brings some very imprecise functionality. Automatic unsubscribe is an
                example. For years, end users have been told not to trust email unsubscribe links, to avoid
                confirming that the message was received and opened. So, many users hit the spam button as
                a way of unsubscribing. Users have to trust their ISP to not get into agreements with
                spammers, in the strict sense of the latter term.</p>

                <p>The spam button may also be used in error, as a means of expressing disagreement with the
                message content, or as a means of expressing antipathy towards the message sender. Using
                the same button for multiple conditions implies guesswork in interpreting the data.</p>

                <p>The bottom line, however, is that the benefits of FBLs far outweigh the cons and the the
                receiver of the Feedback loop must descide on its correct action.</p>
            ",
    ],

    'FREAK_VULNERABLE_SERVER' => [
        'name'        => 'FREAK kwetsbare server',
        'description' => "

            <h2>What is a 'FREAK Vulnerable Server'?</h2>

            <p>Servers that accept RSA_EXPORT cipher suites put their users at risk from the FREAK
            attack. Using Internet-wide scanning, we have been performing daily tests of all
            HTTPS servers at public IP addresses to determine whether they allow this weakened
            encryption. More than a third of all servers with browser-trusted certificates are
            at risk.</p>

            <h2>Why would this be bad?</h2>

            <p>Servers that accept RSA_EXPORT cipher suites put their users at risk from the FREAK
            attack. The FREAK attack is possible when a vulnerable browser connects to a
            susceptible web server—a server that accepts “export-grade” encryption.</p>

            <p>It allows an attacker to intercept HTTPS connections between vulnerable clients and
            servers and force them to use weakened encryption, which the attacker can break to
            steal or manipulate sensitive data. </p>

            <p>The factoring of such 512-bit export keys can be done with a cluster of EC2 virtual
            servers, and can be done in about 7.5 hours for $104 in EC2 time. The attacker
            factors the RSA modulus to recover the corresponding RSA decryption key. When the
            client encrypts the 'pre-master secret' to the server, the attacker can now decrypt
            it to recover the TLS 'master secret'. From here on out, the attacker sees plaintext
            and can inject anything it wants.</p>

            <h2>Recommended action</h2>

            <p>You should immediately disable support for TLS export cipher suites. While you’re
            at it, you should also disable other cipher suites that are known to be insecure
            and enable forward secrecy. For instructions on how to secure popular HTTPS server
            software, we recommend Mozilla’s security configuration guide and their SSL configuration
            generator. We also recommend testing your configuration with the Qualys SSL Labs
            SSL Server Test tool</p>

            <h2>Tips how to resolve this matter</h2>

            <p>With apache server add the following to your SSL section(s):

            SSLCipherSuite ALL:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!MD5:!PSK</p>

            <h2>Getting more information</h2>

            <a target'_blank' href='https://freakattack.com/'>Tracking the FREAK Attack</a><br>
            <a target'_blank' href='https://www.ssllabs.com/ssltest/'>SSL Server Testtool.</a><br>
            <a target'_blank' href='https://wiki.mozilla.org/Security/Server_Side_TLS#Recommended_configurations'>Mozilla’s security configuration guide</a><br>
            <a target'_blank' href='https://mozilla.github.io/server-side-tls/ssl-config-generator/'>SSL configuration generator</a><br>

            ",
    ],

    'HARVESTING' => [
        'name'        => 'Harvesting',
        'description' => "

            <h2>What is 'Harvesting'?</h2>

            <p>Email harvesting is the process of obtaining lists of email addresses or accounts
            using various methods for use in bulk email or other purposes usually
            like seding SPAM or gaining access to the system.</p>

            <p>A common method is the use of special software known as 'harvesting bots'
            or 'harvesters', which spider web pages, mailing list archives, internet
            forums and other online sources to obtain email addresses from public data.</p>

            <p>Another method addresses, known as a directory harvest attack, works
            by connecting to mailservers and guessing an email address using common
            usernames in email addresses at that domain.</p>

            <h2>Why would this be bad?</h2>

            <p>Sending unsolicited email to recipients gathered by means of harvesting
            is illegal and not allowed by our terms of service.</p>

            <h2>Recommended action</h2>

            <p>If you are in fact knowingly harvesting email addresses, we recommend
            you to stop doing that to prevent further escalation.</p>

            <h2>Tips to resolve this matter</h2>

            <p>If this is not the case, make sure your server and your hosted websites
            have not been compromised.</p>

            <h2>Getting more information</h2>

            ",
    ],

    'NOTICE_AND_TAKEDOWN_REQUEST' => [
        'name'        => 'Notice and Takedown verzoek',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'OPEN_CHARGEN_SERVER' => [
        'name'        => 'Open Chargen Server',
        'description' => "
            <h2>What is an 'Open Chargen server'?</h2>

            <p>The Character Generator Protocol (CHARGEN) is a service thats intended
            for testing, debugging, and measurement purposes. The protocol is rarely
            used, as its design flaws allow ready misuse. A host may connect to a
            server that supports the Character Generator Protocol on either
            Transmission Control Protocol (TCP) or User Datagram Protocol (UDP) port
            number 19. Upon opening a TCP connection, the server starts sending
            arbitrary characters to the connecting host and continues until the host
            closes the connection. In the UDP implementation of the protocol, the
            server sends a UDP datagram containing a random number (between 0 and 512)
            of characters every time it receives a datagram from the connecting host.
            Any data received by the server is discarded.</p>

            <h2>Why would this be bad?</h2>

            <p>Running an open (UDP) service is not bad on its own and it is mostly
            a required dependancy when installing a system.
            Unfortunately, hackers have also found this feature useful in performing a
            special type of DDoS attack called a 'Amplification Attack'.</p>

            <p>The attacker sends a packet apparently from the intended victim to some
            server on the Internet that will reply immediately. Because the source
            IP address is forged, the remote Internet server replies and sends data
            to the victim.</p>

            <p>That has two effects: the actual source of the attack is hidden and is
            very hard to trace, and, if many Internet servers are used, an attack
            can consist of an overwhelming number of packets hitting a victim from
            all over the world.</p>

            <p>But what makes reflection attacks really powerful is when they are
            also amplified: when a small forged packet elicits a large reply from
            the server (or servers). In that case, an attacker can send a small
            packet from a forged source IP address and have the server (or
            servers) send large replies to the victim.</p>

            <p>Amplification attacks like that result in an attacker turning a small
            amount of bandwidth coming from a small number of machines into a massive
            traffic load hitting a victim from around the Internet.</p>

            <h2>Recommended action</h2>

            <p>There is no reason to have this CHARGEN service enabled on a public facing
            interface. You should either stop te service or make sure it is not reachable
            from the internet by using RFC1918 spaces or a firewall.</p>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://dnsamplificationattacks.blogspot.nl/2013/07/source-port-chargen-destination-port.html'>Amplification Attacks Observer</a><br>
            ",
    ],

    'OPEN_DNS_RESOLVER' => [
        'name'        => 'Open DNS Resolver',
        'description' => "
            <h2>What is an 'Open DNS Resolver'?</h2>

            <p>An open DNS server is a DNS server which is willing to resolve
            recursive DNS queries for anyone on the Internet.</p>

            <p>When a DNS server resolves a recursive DNS query, it tracks down
            information about a domain name hosted on some other DNS server
            somewhere else on the Internet (a recursive process involving
            several other DNS servers in the DNS hierarchy).</p>

            <h2>Why would this be bad?</h2>

            <p>Running an open (UDP) service is not bad on its own and it is mostly
            a required dependancy when installing a system.
            Unfortunately, hackers have also found this feature useful in performing a
            special type of DDoS attack called a 'Amplification Attack'.</p>

            <p>The attacker sends a packet apparently from the intended victim to some
            server on the Internet that will reply immediately. Because the source
            IP address is forged, the remote Internet server replies and sends data
            to the victim.</p>

            <p>That has two effects: the actual source of the attack is hidden and is
            very hard to trace, and, if many Internet servers are used, an attack
            can consist of an overwhelming number of packets hitting a victim from
            all over the world.</p>

            <p>But what makes reflection attacks really powerful is when they are
            also amplified: when a small forged packet elicits a large reply from
            the server (or servers). In that case, an attacker can send a small
            packet from a forged source IP address and have the server (or
            servers) send large replies to the victim.</p>

            <p>Amplification attacks like that result in an attacker turning a small
            amount of bandwidth coming from a small number of machines into a massive
            traffic load hitting a victim from around the Internet.</p>

            <h2>Recommended action</h2>

            <p>In most cases the computer has installed a DNS service as an dependancy
            as resolving is needed on that computer. However its only required to do
            local resolving, thus the added 'external access' to this service is
            unneeded. In such cases we recommend either changing the configuration
            of your DNS service or to firewall port 53 for external hosts.</p>

            <p>If you are running a DNS resolver for multiple computers, then consider
            to limit access to the required group of computer or implement safegaurds
            like 'Response Rate Limiting'(DNS-RRL) to prevent a DNS Amplification Attack.</p>

            <h2>Tips to resolve this matter</h2>

            <h3>Firewalling the DNS Service</h3>

            <p>To block incoming remote requests you will need to filter UDP/port 53. You
            service might also be listening on TCP/port 53, however only UDP services
            are used in DNS Amplification Attacks. </p>

            <h3>Bind 9.x Authoritative</h3>

            <p>For BIND 9.x authoritative servers, apply the following global options:
            <br>
            <pre>
              options {
                  recursion no;
                  additional-from-cache no;
              };
            </pre>
            <br>
            Beginning with BIND versions 9.4 and later, most configurations would default to a closed resolver. Those running earlier versions should upgrade if possible.<br>
            </p>

            <p><h3>Bind 9.x Caching</h3>

            For BIND 9.x caching servers, additionally create access control lists and use 'views' to explicitly permit a limited set of source addresses from your trusted network issue queries to your caching server:
            <pre>
              # example only, replace 192.0.2.0/24 a list of your CIDR blocks
              acl 'trusted' {
                  192.0.2.0/24;
              };

              options {
                  recursion no;
                  additional-from-cache no;
                  allow-query { none; };
              };

              view 'trusted' in {
                  match-clients { trusted; };
                  allow-query { trusted; };
                  recursion yes;
                  additional-from-cache yes;
              };
            </pre></p>

            <h3>Windows Systems</h3>

            <p>Please see the following Microsoft TechNet examples:<br>
            <br>
            <a target'_blank' href='http://technet.microsoft.com/en-us/library/cc771738.aspx'>Disabling recursion on Windows Server 2008 R2 systems</a><br>
            <a target'_blank' href='http://technet.microsoft.com/en-us/library/cc787602.aspx'>Disabling recursion on older Windows Server systems</a><br>
            <a target'_blank' href='http://technet.microsoft.com/en-us/library/cc773370(WS.10).aspx'>Acting as a non-recursive forwarder</a> (See the 'Notes' section under the 'Using the Windows interface' instructions)<br>
            </p>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://dns.measurement-factory.com/cgi-bin/openresolvercheck.pl/'>A website for (re)checking your DNS service</a><br>
            <a target'_blank' href='http://openresolverproject.org/'>the Open Resolver Project</a><br>
            <a target'_blank' href='http://www.youtube.com/watch?v=XhSTlqYIQnI' Team Cymru 'Who and Why Show' - DNS Amplification Attacks</a> (5 minute YouTube video)<br>
            <a target'_blank' href='http://www.team-cymru.com/ReadingRoom/Whitepapers/2009/recursion.pdf' Team Cymru DNS Open Recursion Whitepaper</a><br>
            <a target'_blank' href='http://www.us-cert.gov/reading_room/DNS-recursion033006.pdf' http://www.us-cert.gov/reading_room/DNS-recursion033006.pdf</a><br>
            <a target'_blank' href='http://www.cymru.com/Documents/secure-bind-template.html'>http://www.cymru.com/Documents/secure-bind-template.html</a><br>
            <a target'_blank' href='http://www.ripe.net/ripe/meetings/ripe-52/presentations/ripe52-plenary-dnsamp.pdf' http://www.ripe.net/ripe/meetings/ripe-52/presentations/ripe52-plenary-dnsamp.pdf</a><br>
            <a target'_blank' href='http://www.icann.org/committees/security/dns-ddos-advisory-31mar06.pdf'>http://www.icann.org/committees/security/dns-ddos-advisory-31mar06.pdf</a><br>
            <a target'_blank' href='http://www.secureworks.com/research/threats/dns-amplification/?threat=dns-amplification'>http://www.secureworks.com/research/threats/dns-amplification/?threat=dns-amplification</a><br>
            <a target'_blank' href='http://www.icann.org/en/groups/ssac/documents/sac-065-en.pdf'>http://www.icann.org/en/groups/ssac/documents/sac-065-en.pdf</a><br>

            ",
    ],

    'OPEN_MDNS_SERVICE' => [
        'name'        => 'Open mDNS Service',
        'description' => "
            <h2>What is an 'Open mDNS Service'?</h2>

            <p>An Open mDNS Service is a mDNS server which is willing to resolve
            recursive DNS queries for anyone on the Internet.</p>

            <p>When a DNS server resolves a recursive DNS query, it tracks down
            information about a domain name hosted on some other DNS server
            somewhere else on the Internet (a recursive process involving
            several other DNS servers in the DNS hierarchy).</p>

            <h2>Why would this be bad?</h2>

            <p>Running an open (UDP) service is not bad on its own and it is mostly
            a required dependancy when installing a system.
            Unfortunately, hackers have also found this feature useful in performing a
            special type of DDoS attack called a 'Amplification Attack'.</p>

            <p>The attacker sends a packet apparently from the intended victim to some
            server on the Internet that will reply immediately. Because the source
            IP address is forged, the remote Internet server replies and sends data
            to the victim.</p>

            <p>That has two effects: the actual source of the attack is hidden and is
            very hard to trace, and, if many Internet servers are used, an attack
            can consist of an overwhelming number of packets hitting a victim from
            all over the world.</p>

            <p>But what makes reflection attacks really powerful is when they are
            also amplified: when a small forged packet elicits a large reply from
            the server (or servers). In that case, an attacker can send a small
            packet from a forged source IP address and have the server (or
            servers) send large replies to the victim.</p>

            <p>Amplification attacks like that result in an attacker turning a small
            amount of bandwidth coming from a small number of machines into a massive
            traffic load hitting a victim from around the Internet.</p>

            <h2>Recommended action</h2>

            <p>In most cases the computer has installed a DNS service as an dependancy
            as resolving is needed on that computer. However its only required to do
            local resolving, thus the added 'external access' to this service is
            unneeded. In such cases we recommend either changing the configuration
            of your DNS service or to firewall port 5353 for external hosts.</p>

            <p>If you are running a DNS resolver for multiple computers, then consider
            to limit access to the required group of computer or implement safegaurds
            like 'Response Rate Limiting'(DNS-RRL) to prevent a DNS Amplification Attack.</p>

            <h2>Tips to resolve this matter</h2>

            <h3>Firewalling the DNS Service</h3>

            <p>To block incoming remote requests you will need to filter UDP/port 5353. You
            service might also be listening on TCP/port 5353, however only UDP services
            are used in DNS Amplification Attacks. </p>

            ",
    ],

    'OPEN_IMPI_SERVER' => [
        'name'        => 'Open IPMI Server',
        'description' => "
            <h2>What is an 'Open IPMI Server'?</h2>

            <p>IPMI defines a set of interfaces used by system administrators for
            out-of-band management of computer systems and monitoring of their operation.
            For example, IPMI provides a way to manage a computer that may be powered off
            or otherwise unresponsive by using a network connection to the hardware
            rather than to an operating system or login shell.</p>

            <p>IPMI is integrated on most server systems, however under different names like
            iRMC, ILOM, BMC, iDRAC, etc. Each vender has their own implementation of IPMI,
            but the base is the same on each of them: It allows access to hardware outside
            your operating system (and its locally installed firewall!).</p>

            <h2>Why would this be bad?</h2>

            <p>IPMI is the base of most of the Out Of Band / Lights Out management suites and
            is implemented by the server's Baseboard Management Controller (BMC). The BMC
            has near complete access and control of the server's resources, including, but
            not limited to, memory, power, and storage. Anyone that can control your BMC
            (via IPMI), can control your server.</p>

            <p>IPMI instances in general are known to contain a variety of vulnerabilities,
            some more serious than other. In short; you really do not want to expose IPMI
            to the internet. </p>

            <h2>Recommended action</h2>

            <p>Implement a seperate network for hosting these Out-Of-Band management entries and
            place them in a RFC1918(non public IP) space in combination with a VPN or add a
            hardware firewall in front of this network that filters access.</p>

            <p>Some IPMI implementations do offer some kind of what they call firewalling, however
            we havent come across an implementation that actually fully protects the IPMI
            interface from outside influance.</p>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://fish2.com/ipmi/'>Dan Farmer on IPMI security issues</a><br>
            <a target'_blank' href='https://www.us-cert.gov/ncas/alerts/TA13-207A'>US-CERT alert TA13-207A</a><br>

            ",
    ],

    'OPEN_MEMCACHED_SERVER' => [
        'name'        => 'Open Memcached Server',
        'description' => "
            <h2>What is an 'Open Memcached Server'?</h2>

            <p>Memcached is an in-memory key-value store for small chunks of
            arbitrary data (strings, objects) from results of database calls,
            API calls, or page rendering. Its intended for use in speeding up
            dynamic web applications by alleviating database load</p>

            <h2>Why would this be bad?</h2>

            <p>The problem is actually pretty simple - memcached is built for speed,
            not for security, and it does nothing to secure itself. As far as
            memcache's authors are concerned, this isn't a problem... it just
            means you have to secure it yourself. The problem is the people don't
            do that.</p>

            <p>Since its not secure, this means that random people on the internet
            can discover your memcache, read things from it, and even write into
            it. The kinds of security problems that could create are limited only
            by your imagination and the kinds of data you put into your cache.</p>

            <p>Lets assume I have an insecure memcache instance running at my.example.com.
            I can log into that server, and connect to my memcache instance with telnet:

            [dbock@my.example ~]$ telnet localhost 11211
            Trying 127.0.0.1...
            Connected to my.example.com (127.0.0.1).
            Escape character is '^]'.
            add catchphrase 0 0 29
            intelligence for sale or rent

            and memcache returns : STORED

            so far, so good. We just stuck a piece of data into our memcache. but guess
            what? ANYONE, ANYWHERE on the internet can now get that piece of data. Log
            out of your machine and try this from a remote host:

            [dbock@my.desktopmachine] ~]$ telnet my.example.com 11211
            Trying 192.0.32.10...
            Connected to my.example.com (192.0.32.10).
            Escape character is '^]'.
            get catchphrase

            and memcache responds:

            VALUE catchphrase 0 29
            intelligence for sale or rent
            END

            Ouch. We just connected to our server and pulled out our super-secret catch
            phrase. We don't want or need memcache to be listening to the outside world.</p>

            <h2>Recommended action</h2>

            <p>If you are dealing with a multi-server environment, the solution will be more
            complicated than this - you'll want a firewall, a private network, and other
            complexity to separate the 'outside world' from your 'inside world'. If you
            are dealing with one server though, the solution is simple - just don't
            listen to your ethernet connection.</p>

            <p>Mysql has an option to specify what address it listens to... so rather than
            listen to everything, we ony want to listen to the localhost - 127.0.0.1

            On my CentOS box, I can do this by simply editing the file: /etc/sysconfig/memcached
            and changing the line

            OPTIONS=''

            to

            OPTIONS='-l 127.0.0.1'

            and restart memcache with

            sudo /sbin/service memcached restart

            retry the steps above and you'll find that outside the server, telnet can't connect anymore.</p>

            <p>The memcached settings file might be someplace else on other linuxes, depending
            on their file conventions... The memcache installation for your platform should give
            you a clue where this is, as this file is also needed to specify the amount of
            memory in the cache, etc.</p>

            <h2>Tips to resolve this matter</h2>

            <p></p>

            <h2>Getting more information</h2>

            <atarget'_blank'  href='http://blog.codesherpas.com/on_the_path/2010/08/securing-memcache-in-2-minutes.html'>David Bock - Securing Memcache in 2 Minutes</a><br>

            ",
    ],

    'OPEN_MSSQL_SERVER' => [
        'name'        => 'Open Microsoft SQL Server',
        'description' => "
            <h2>What is an 'Open Microsoft SQL Server'?</h2>

            <p>Microsoft SQL Server is a relational database management system developed
            by Microsoft. As a database, it is a software product whose primary function
            is to store and retrieve data as requested by other software applications,
            be it those on the same computer or those running on another computer across
            a network (including the Internet). There are at least a dozen different
            editions of Microsoft SQL Server aimed at different audiences and for
            workloads ranging from small single-machine applications to large
            Internet-facing applications with many concurrent users. Its primary query
            languages are T-SQL and ANSI SQL.</p>

            <h2>Why would this be bad?</h2>

            <p>This service has the potential to expose information about a clients network
            on which this service is accessible and the service itself can be used in UDP
            amplification attacks. In addition it opens up your system to 0-day attacks or
            worm/virus infections that exploit a vulnarability in Windows to gain access
            to your system.</p>

            <h2>Recommended action</h2>

            <p>Either use the Windows Firewall or even better an external firewall to prevent
            access to Netbios (and other Windows ports). The windows firewall has an nasty
            way of trying to think for himself and for example automaticly starts to open
            ports if you install something that uses Netbios. In all cases the
            administrator is unaware of these open ports.</p>

            <h2>Tips to resolve this matter</h2>

            <p>By default, Microsoft Windows enables the Windows Firewall, which closes port
            1433 to prevent Internet computers from connecting to a default instance of SQL
            Server on your computer. Connections to the default instance using TCP/IP are not
            possible unless you reopen port 1433. If you need access from remote machines to
            your Microsoft SQL server, then only allow the required hosts and close down
            world wide accesss to the SQL server.</p>

            <p>Ports used by Microsoft are: TCP/1433, UDP/1434, TCP/1434, TCP/4022, TCP/135, TCP/2383.
            In addition the SQL Server uses a randomly assign dynamic port for named instance! </p>

            <h2>Getting more information</h2>

            <a target'_blank' href='https://msdn.microsoft.com/en-us/library/ms175043.aspx'>Configure a Windows Firewall for Database Engine Access</a><br>

            ",
    ],

    'OPEN_MONGODB_SERVER' => [
        'name'        => 'Open MongoDB Server',
        'description' => "
            <h2>What is an 'Open MongoDB Server'?</h2>

            <p>MongoDB is a cross-platform document-oriented database. Classified as a NoSQL
            database, MongoDB eschews the traditional table-based relational database
            structure in favor of JSON-like documents with dynamic schemas (MongoDB calls
            the format BSON), making the integration of data in certain types of
            applications easier and faster.</p>

            <h2>Why would this be bad?</h2>

            <p>Your system has a MongoDB NoSQL database (see www.mongodb.org for more
            information) running which is accessible on the internet. While authentication
            is available for MongoDB, in many instances this authentication is not enabled.</p>

            <p>This enables an attacker, without circumventing any security measures, to get
            read-and-write access to these databases, many of which contain sensitive
            customer data or live backends of Web shops</p>

            <h2>Recommended action</h2>

            <p>Either bind this service only to non-public facing connections or add a firewall
            to block the port MongoDB is running on.</p>

            <h2>Tips to resolve this matter</h2>

            <p>The MongoDB service default configuration enables local access only. Its main
            configuration file is usually found at:

            Linux: /etc/mongodb.conf

            BSD: /usr/local/etc/mongodb.conf

            Windows: no default path; usually assigned on setup</p>

            <p>Many precompiled packages of MongoDB already ship with a default configuration
            that binds the service after its installation only to localhost:

            bindIp: 127.0.0.1
            port: 27017</p>

            <p>This allows access from services running on the same physical or virtual host
            and denies everything else. No other security feature like traffic encryption
            or access control is enabled by default. This configuration is acceptable, if
            the use-case scenario includes only services that need to access MongoDB from
            the same host as the database service.</p>

            <p>However, a common setup and scalable solution for most Internet services is to
            have a database server running on one physical machine, while the services
            using this database service are (often virtualized) running on another machine.
            In this case, the easiest solution is to comment out the flag 'bind.ip =
            127.0.0.1' or to remove it completely, which defaults to accepting all network
            connections to the database. If access is possible from untrusted machines
            (e.g., from the Internet) outside the trusted network, it is crucial to also
            set up transfer encryption and proper access control.</p>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://cispa.saarland/wp-content/uploads/2015/02/MongoDB_documentation.pdf'>MongoDB databases at risk</a><br>

            ",
    ],

    'OPEN_NATPMP_SERVER' => [
        'name'        => 'Open NAT_PMP Server',
        'description' => "
            <h2>What is an 'Open NAT-PMP Server'?</h2>

            <p>The NAT Port Mapping Protocol (NAT-PMP) is a network protocol for establishing
            network address translation (NAT) settings and port forwarding configurations
            automatically without user effort. The protocol automatically determines the
            external IPv4 address of a NAT gateway, and provides means for an application
            to communicate the parameters for communication to peers.</p>

            <h2>Why would this be bad?</h2>

            <p>Many NAT-PMP devices are incorrectly configured, allowing them to field requests
            received on external network interfaces or map forwarding routes to addresses
            other than that of the requesting host, making them potentially vulnerable to
            information disclosure and malicious port mapping requests. The responses from
            Open NAT-PMP Servers represent two types of vulnerabilities; malicious port
            mapping manipulation and information disclosure about the NAT-PMP device. These
            can be broken down into 5 specific issues, outlined below:</p>

            - Interception of Internal NAT Traffic (2.5% of responding devices)
            - Interception of External Traffic (86% of responding devices)
            - Access to Internal NAT Client Services (88% of responding devices)
            - DoS Against Host Services (88% of responding devices)
            - Information Disclosure about the NAT-PMP device (100% of responding devices)

            <p>In short: A remote, unauthenticated attacker may be able to gather information
            about a NAT device, manipulate its port mapping, intercept its private and public
            traffic, access its private client services, and block its host services.</p>

            <h2>Recommended action</h2>

            <p>Developers and administrators implementing NAT-PMP should exercise care to ensure
            that devices are configured securely, specifically that</p>

            - the LAN and WAN interfaces are correctly assigned,
            - NAT-PMP requests are only accepted on internal interfaces, and
            - port mappings are only opened for the requesting internal IP address.

            <h2>Tips to resolve this matter</h2>

            - Deploy firewall rules to block untrusted hosts from being able to access port 5351/udp.
            - Consider disabling NAT-PMP on the device if it is not absolutely necessary.

            <h2>Getting more information</h2>

            <a target'_blank' href='http://www.kb.cert.org/vuls/id/184540'>Incorrect implementation of NAT-PMP in multiple devices</a><br>
            <a target'_blank' href='https://community.rapid7.com/community/metasploit/blog/2014/10/21/r7-2014-17-nat-pmp-implementation-and-configuration-vulnerabilities'>NAT-PMP Implementation and Configuration Vulnerabilities</a>

            ",
    ],

    'OPEN_NTP_SERVER' => [
        'name'        => 'Open NTP Server',
        'description' => "
            <h2>What is an 'Open NTP Server'?</h2>

            <p>Network Time Protocol (NTP) is a networking protocol for clock
            synchronization between computer systems over packet-switched,
            variable-latency data networks. In operation since before 1985, NTP
            is one of the oldest Internet protocols in current use.</p>

            <h2>Why would this be bad?</h2>

            <p>Running an open (UDP) service is not bad on its own and it is mostly
            a required dependancy when installing a system.
            Unfortunately, hackers have also found this feature useful in performing a
            special type of DDoS attack called a 'Amplification Attack'.</p>

            <p>The attacker sends a packet apparently from the intended victim to some
            server on the Internet that will reply immediately. Because the source
            IP address is forged, the remote Internet server replies and sends data
            to the victim.</p>

            <p>That has two effects: the actual source of the attack is hidden and is
            very hard to trace, and, if many Internet servers are used, an attack
            can consist of an overwhelming number of packets hitting a victim from
            all over the world.</p>

            <p>But what makes reflection attacks really powerful is when they are
            also amplified: when a small forged packet elicits a large reply from
            the server (or servers). In that case, an attacker can send a small
            packet 'from' a forged source IP address and have the server (or
            servers) send large replies to the victim.</p>

            <p>Amplification attacks like that result in an attacker turning a small
            amount of bandwidth coming from a small number of machines into a massive
            traffic load hitting a victim from around the Internet.</p>

            <h2>Recommended action</h2>

            <p>In most cases the computer has installed a NTP service as an dependancy
            as timekeeping is needed on that computer. However its only required to do
            local timekeeping, thus the added 'external access' to this service is
            unneeded. In such cases we recommend either changing the configuration
            of your NTP service or to firewall port 123 for external hosts.</p>

            <p>If you are running a NTP Server for multiple computers, then consider
            to limit access to the required group of computer or implement safegaurds
            like ... to prevent a DNS Amplification Attack.</p>


            <h2>Tips to resolve this matter</h2>

            <h3>UNIX/Linux ntpd</h3>

            <p>The following configuration is for a UNIX/Linux machine to act as simply
            an NTP client and never to allow NTP queries to it except from the loopback
            address:</p>

            <pre># by default act only as a basic NTP client
            restrict -4 default nomodify nopeer noquery notrap
            restrict -6 default nomodify nopeer noquery notrap
            # allow NTP messages from the loopback address, useful for debugging
            restrict 127.0.0.1
            restrict ::1
            # server(s) we time sync to
            server 192.0.2.1
            server 2001:DB8::1
            server time.example.net
            </pre>

            <p>You can use your standard host firewall filtering capabilities to limit
            who the NTP process talks to.  If you're using Linux and the host is acting
            as an NTP client only, the following iptables rules could be adapted to shield
            your NTP listener from unwanted remote hosts.</p>

            <pre>-A INPUT -s 0/0 -d 0/0 -p udp --source-port 123:123 -m state --state ESTABLISHED -j ACCEPT
            -A OUTPUT -s 0/0 -d 0/0 -p udp --destination-port 123:123 -m state --state NEW,ESTABLISHED -j ACCEPT
            </pre>

            <p>Authentication with the reference NTP software on UNIX can be done using
            symmetric key encryption, much like in Cisco IOS and Juniper JUNOS, using
            MD5.  However, a public key-based approach called 'AutoKey' is also
            available, which is generally be considered to be even more secure.  For
            more information about these options, see the
            <a href='http://www.eecis.udel.edu/~mills/ntp/html/authopt.html' target='_blank'>NTP authentication
            options page</a> and the <a href='http://support.ntp.org/bin/view/Support/ConfiguringAutokey' target='_blank'>
            Configuring Autokey documentation</a>.</p>


            <h2>Getting more information</h2>

            <a target'_blank' href='http://www.team-cymru.org/secure-ntp-template.html'>Examples in securing a NTP service</a>
            <a target'_blank' href='http://www.internetsociety.org/sites/default/files/01_5.pdf'>Amplification Hell: Revisiting Network Protocols for DDoS Abuse</a>

            ",
    ],

    'OPEN_NETBIOS_SERVER' => [
        'name'        => 'Open Netbios Server',
        'description' => "
            <h2>What is an 'Open Netbios server'?</h2>

            <p>NetBIOS is a transport protocol that Microsoft Windows systems use to share
            resources.  For example, if a PC running Windows wants to connect to and access a
            share on a file server, it probably uses NetBIOS.  There have been some changes in
            recent days, however, that allow this connection without it. SMB, the method used
            to access file and printer shares, can also run independently of NetBIOS over TCP
            ports 139 and 445.  Both of these approaches, however, tend to increase the attack
            surface of a network.</p>

            <h2>Why would this be bad?</h2>

            <p>The ports that that are open to the Internet are UDP/137, UDP/138, and TCP/139.
            Unfortunately, the most popular attacker target is NetBIOS and against these
            ports.</p>

            <p>Once an attacker discovers an active port 139 on a device, he can run NBSTAT to
            begin the very important first step of an attack—footprinting.  With the NBSTAT
            command, he can obtain some or all of the following information:</p>

            <ul>
            <li>Computer name</li>
            <li>Contents of the remote name cache, including IP addresses</li>
            <li>A list of local NetBIOS names</li>
            <li>A list of names resolved by broadcast or via WINS</li>
            <li>Contents of the session table with the destination IP addresses</li>
            </ul>

            <p>With this information, the attacker has information about the OS, services, and major
            applications running on the system. He also has private IP addresses that the LAN/WAN
            and security engineers have tried hard to hide behind NAT.  And that’s not all.  The
            lists provided by running NBSTAT also include user IDs.</p>

            <p>If null sessions are allowed against IPC$, it isn’t difficult to take the next step
            and connect to the target device.  This connection provides a list of all available
            shares.</p>

            <p>These services have the potential to be used in amplification attacks by criminals
            that wish to perform denial of service attacks. In addition it opens up your system
            to 0-day attacks or worm/virus infections that exploit a vulnarability in Windows to
            gain access to your system.</p>

            <h2>Recommended action</h2>

            <p>Either use the Windows Firewall or even better an external firewall to prevent access
            to Netbios (and other Windows ports). The windows firewall has an nasty way of trying
            to think for himself and for example automaticly starts to open ports if you install
            something that uses Netbios. In all cases the administrator is unaware of these open
            ports.</p>

            <p>If you really need NETBIOS open for the entire world, then ensure that the exposed
            system(s) are hardened by:</p>
            <ul>
            <li>Disabling the system’s ability to support null sessions</li>
            <li>Defining very strong passwords for the local administrator accounts</li>
            <li>Defining very strong passwords for shares, assuming you absolutely have to have shares on exposed systems</li>
            <li>Keeping the Guest account disabled</li>
            <li>Under no circumstances allowing access to the root of a hard drive via a share</li>
            <li>Under no circumstances sharing the Windows or WinNT directories or any directory located beneath them</li>
            </ul>

            <h2>Tips to resolve this matter</h2>

            <p>In a privileged DOS box run the following commands:</p>

            netsh advfirewall firewall add rule name='NetBIOS UDP Port 137' dir=in action=deny protocol=UDP localport=137<br>
            netsh advfirewall firewall add rule name='NetBIOS UDP Port 137' dir=out action=deny protocol=UDP localport=137<br>
            netsh advfirewall firewall add rule name='NetBIOS UDP Port 138' dir=in action=deny protocol=UDP localport=138<br>
            netsh advfirewall firewall add rule name='NetBIOS UDP Port 138' dir=out action=deny protocol=UDP localport=138<br>
            netsh advfirewall firewall add rule name='NetBIOS TCP Port 139' dir=in action=deny protocol=TCP localport=139<br>
            netsh advfirewall firewall add rule name='NetBIOS TCP Port 139' dir=out action=deny protocol=TCP localport=139<br>

            <h2>Getting more information</h2>

            <a target'_blank' href='https://technet.microsoft.com/en-us/library/cc940063.aspx'>Microsoft NetBIOS Over TCP/IP guide</a>

            ",
    ],

    'OPEN_QOTD_SERVER' => [
        'name'        => 'Open QOTD Server',
        'description' => "
            <h2>What is an 'Open QOTD Server'?</h2>

            <p>The Quote Of The Day (QOTD) service is a member of the Internet protocol
            suite, defined in RFC 865. As indicated there, the QOTD concept predated
            the specification, when QOTD was used by mainframe sysadmins to broadcast
            a daily quote on request by a user. It was then formally codified both
            for prior purposes as well as for testing and measurement purposes in RFC 865.</p>

            <h2>Why would this be bad?</h2>

            <p>Running an open (UDP) service is not bad on its own and it is mostly
            a required dependancy when installing a system.
            Unfortunately, hackers have also found this feature useful in performing a
            special type of DDoS attack called a 'Amplification Attack'.</p>

            <p>The attacker sends a packet apparently from the intended victim to some
            server on the Internet that will reply immediately. Because the source
            IP address is forged, the remote Internet server replies and sends data
            to the victim.</p>

            <p>That has two effects: the actual source of the attack is hidden and is
            very hard to trace, and, if many Internet servers are used, an attack
            can consist of an overwhelming number of packets hitting a victim from
            all over the world.</p>

            <p>But what makes reflection attacks really powerful is when they are
            also amplified: when a small forged packet elicits a large reply from
            the server (or servers). In that case, an attacker can send a small
            packet from a forged source IP address and have the server (or
            servers) send large replies to the victim.</p>

            <p>Amplification attacks like that result in an attacker turning a small
            amount of bandwidth coming from a small number of machines into a massive
            traffic load hitting a victim from around the Internet.</p>

            <h2>Recommended action</h2>

            <p>There is no reason to have this QOTD service enabled on a public facing
            interface. You should either stop te service or make sure it is not reachable
            from the internet by using RFC1918 spaces or a firewall.</p>

            <h2>Tips to resolve this matter</h2>

            <h3>Unix/Linux</h3>

            <p>To disable QOTD when started from inetd:</p>

            <ul>
            <li>Edit the /etc/inetd.conf (or equivalent) file.</li>
            <li>Locate the line that controls the qotd daemon.</li>
            <li>Type a # at the beginning of the line to comment out the daemon.</li>
            <li>Restart inetd.</li>
            </ul>

            <h3>Windows</h3>

            <p>Set the following registry keys to 0:</p>
            <pre>
            HKLM\System\CurrentControlSet\Services\SimpTCP\Parameters\EnableTcpQotd
            HKLM\System\CurrentControlSet\Services\SimpTCP\Parameters\EnableUdpQotd
            </pre>

            <p>Then launch cmd.exe and type the following commands to restart the service:</p>
            <pre>
            net stop simptcp
            net start simptcp
            </pre>

            ",
    ],

    'OPEN_REDIS_SERVER' => [
        'name'        => 'Open REDIS Server',
        'description' => "
            <h2>What is an 'Open REDIS Server'?</h2>

            <p>Redis clients communicate with the Redis server using a protocol called
            RESP (REdis Serialization Protocol). While the protocol was designed
            specifically for Redis, it can be used for other client-server software
            projects.</p>

            <h2>Why would this be bad?</h2>

            <p>Redis is designed to be accessed by trusted clients inside trusted environments.
            This means that usually it is not a good idea to expose the Redis instance
            directly to the internet or, in general, to an environment where untrusted
            clients can directly access the Redis TCP port or UNIX socket.</p>

            <p>For instance, in the common context of a web application implemented using
            Redis as a database, cache, or messaging system, the clients inside the
            frontend (web side) of the application will query Redis to generate pages or
            to perform operations requested or triggered by the web application user.</p>

            <p>In this case, the web application mediates access between Redis and untrusted
            clients (the user browsers accessing the web application). This is a specific
            example, but, in general, untrusted access to Redis should always be mediated
            by a layer implementing ACLs, validating user input, and deciding what
            operations to perform against the Redis instance. In general, Redis is not
            optimized for maximum security but for maximum performance and simplicity.</p>

            <h2>Recommended action</h2>

            <p>Access to the Redis port should be denied to everybody but trusted clients in
            the network, so the servers running Redis should be directly accessible only
            by the computers implementing the application using Redis.</p>

            <p>In the common case of a single computer directly exposed to the internet, such
            as a virtualized Linux instance (Linode, EC2, ...), the Redis port should be
            firewalled to prevent access from the outside. Clients will still be able to
            access Redis using the loopback interface.</p>

            <p></p>

            <h2>Tips to resolve this matter</h2>

            <h3>Firewalling remote access</h3>

            <p>Simplest way is to block the default port TCP/6379 (or whatever port is listed
            in the report) and only allow IP's that should actually have access to this service.</p>

            <h3>Blocking remote access</h3>

            <p>It is possible to bind Redis to a single interface by adding a line like the following to the redis.conf file:</p>

            <pre>
                bind 127.0.0.1
            </pre>

            <h3>Enabling Authentication feature</h3>

            <p>While Redis does not try to implement Access Control, it provides a tiny layer
            of authentication that is optionally turned on editing the redis.conf file. When
            the authorization layer is enabled, Redis will refuse any query by unauthenticated
            clients. A client can authenticate itself by sending the AUTH command followed by
            the password.</p>

            <p>The password is set by the system administrator in clear text inside the redis.conf
            file. It should be long enough to prevent brute force attacks for two reasons:</p>

            <ul>
            <li>Redis is very fast at serving queries. Many passwords per second can be tested by an external client.</li>
            <li>The Redis password is stored inside the redis.conf file and inside the client configuration, so it does not need to be remembered by the system administrator, and thus it can be very long.</li>
            </ul>

            <p>The goal of the authentication layer is to optionally provide a layer of redundancy.
            If firewalling or any other system implemented to protect Redis from external attackers
            fail, an external client will still not be able to access the Redis instance without
            knowledge of the authentication password. The AUTH command, like every other Redis
            command, is sent unencrypted, so it does not protect against an attacker that has
            enough access to the network to perform eavesdropping.</p>


            <h2>Getting more information</h2>

            <a target'_blank' href='http://redis.io/topics/security'>Redis Security advisory</a><br>

            ",
    ],

    'OPEN_SNMP_SERVER' => [
        'name'        => 'Open SNMP Server',
        'description' => "
            <h2>What is an 'Open SNMP Server'?</h2>

            <p>Simple Network Management Protocol (SNMP) is a popular protocol for network
            management. It is used for collecting information from, and configuring,
            network devices, such as servers, printers, hubs, switches, and routers on an
            Internet Protocol (IP) network.</p>

            <h2>Why would this be bad?</h2>

            <p>Open SNMP Servers can be used to collect privileged information from the
            system or even to write new 'settings' to the system if not correctly
            configured.</p>

            <p>In addition running an open (UDP) service is not bad on its own and it
            is mostly a required dependancy when installing a system.
            Unfortunately, hackers have also found this feature useful in performing a
            special type of DDoS attack called a 'Amplification Attack'.</p>

            <p>The attacker sends a packet apparently from the intended victim to some
            server on the Internet that will reply immediately. Because the source
            IP address is forged, the remote Internet server replies and sends data
            to the victim.</p>

            <p>That has two effects: the actual source of the attack is hidden and is
            very hard to trace, and, if many Internet servers are used, an attack
            can consist of an overwhelming number of packets hitting a victim from
            all over the world.</p>

            <p>But what makes reflection attacks really powerful is when they are
            also amplified: when a small forged packet elicits a large reply from
            the server (or servers). In that case, an attacker can send a small
            packet from a forged source IP address and have the server (or
            servers) send large replies to the victim.</p>

            <p>Amplification attacks like that result in an attacker turning a small
            amount of bandwidth coming from a small number of machines into a massive
            traffic load hitting a victim from around the Internet.</p>

            <h2>Recommended action</h2>

            <ul>
            <li>Use firewalling to block UDP/161 entirely or only allow the hosts that
            need access to this service</li>
            <li>Update the SNMP configuration to use a different community string then public. Something
            strong like ThisIsMyCommunityString</li>
            <li>Update the SNMP configuration to use a host based ACL's in combination with either the 'public'
            community or a string thats more 'secure'</li>
            </ul>

            <h2>Tips to resolve this matter</h2>

            <h3>Windows</h3>
            <ul>
            <li>Click on Windows Key > Administrative Tools > Services.</li>
            <li>Right click on SNMP Service and click on Properties.</li>
            <li>Click on the Security tab.</li>
            <li>Type your randomized 8 - 10 character connection string. Be sure to make it Read Only, not Read Write.</li>
            <li>Click on Add.</li>
            <li>Click on OK.</li>
            <li>Finally restart the SNMP service</li>
            </ul>

            <h3>Linux</h3>
            <p>Edit the SNMP configuration file, which is useally located at: /etc/snmp/snmpd.conf<p>

            <p>Change/Modify line(s) as follows:</p>
            <p>Find the following Line:</p>
            <pre>com2sec notConfigUser  default       public</pre>
            Replace with (make sure you replace 192.168.0.0/24 with your network/subnet) the following lines:
            <pre>com2sec local     localhost           public
            com2sec mynetwork 192.168.0.0/24      public</pre>
            <p>Scroll down a bit and change :</p>
            <p>Find Lines:</p>
            <pre>group   notConfigGroup v1           notConfigUser
            group   notConfigGroup v2c           notConfigUser</pre>
            <p>Replace with:</p>
            <pre>group MyRWGroup v1         local
            group MyRWGroup v2c        local
            group MyRWGroup usm        local
            group MyROGroup v1         mynetwork
            group MyROGroup v2c        mynetwork
            group MyROGroup usm        mynetwork</pre>
            <p>Again scroll down a bit and locate the following line:</p>
            <p>Find line:</p>
            <pre>view    systemview     included      system</pre>
            <p>Replace with:</p>
            <pre>view all    included  .1                               80</pre>
            <p>Again scroll down a bit and change the following line:</p>
            <p>Find line:</p>
            <pre>access  notConfigGroup ''      any       noauth    exact  systemview none none</pre>
            <p>Replace with:</p>
            <pre>access MyROGroup ''      any       noauth    exact  all    none   none<br>access MyRWGroup ''      any       noauth    exact  all    all    none</pre>
            <p>Scroll down a bit and change the following lines:</p>
            <p>Find lines:</p>
            <pre>syslocation Unknown (edit /etc/snmp/snmpd.conf)
            syscontact Root <root@localhost> (configure /etc/snmp/snmp.local.conf)</root@localhost></pre>
            <b>Replace with (make sure you supply appropriate values), for example:</b>
            <pre>syslocation Linux (RH3_UP2), Home Linux Router.<br>syscontact YourNameHere &lt;you@example.com&gt;</pre>

            <p>restart your snmp server and test it</p>

            <h2>Getting more information</h2>

            ",
    ],

    'OPEN_SSDP_SERVER' => [
        'name'        => 'Open SSDP Server',
        'description' => "
            <h2>What is an 'Open SSDP Server'?</h2>

            <p>The Simple Service Discovery Protocol (SSDP) is a network protocol
            based on the Internet Protocol Suite for advertisement and discovery of
            network services and presence information. It accomplishes this without
            assistance of server-based configuration mechanisms, such as the Dynamic
            Host Configuration Protocol (DHCP) or the Domain Name System (DNS), and
            without special static configuration of a network host. SSDP is the basis
            of the discovery protocol of Universal Plug and Play (UPnP) and is
            intended for use in residential or small office environments.</p>

            <h2>Why would this be bad?</h2>

            <p>Running an open (UDP) service is not bad on its own and it is mostly
            a required dependancy when installing a system.
            Unfortunately, hackers have also found this feature useful in performing a
            special type of DDoS attack called a 'Amplification Attack'.</p>

            <p>The attacker sends a packet apparently from the intended victim to some
            server on the Internet that will reply immediately. Because the source
            IP address is forged, the remote Internet server replies and sends data
            to the victim.</p>

            <p>That has two effects: the actual source of the attack is hidden and is
            very hard to trace, and, if many Internet servers are used, an attack
            can consist of an overwhelming number of packets hitting a victim from
            all over the world.</p>

            <p>But what makes reflection attacks really powerful is when they are
            also amplified: when a small forged packet elicits a large reply from
            the server (or servers). In that case, an attacker can send a small
            packet from a forged source IP address and have the server (or
            servers) send large replies to the victim.</p>

            <p>Amplification attacks like that result in an attacker turning a small
            amount of bandwidth coming from a small number of machines into a massive
            traffic load hitting a victim from around the Internet.</p>

            <h2>Recommended action</h2>

            <p>There is no reason to have this CHARGEN service enabled on a public facing
            interface. You should either stop te service or make sure it is not reachable
            from the internet by using RFC1918 spaces or a firewall.</p>

            ",
    ],

    'OPEN_TFTP_SERVER' => [
        'name'        => 'Open TFTP Server',
        'description' => "
            <h2>Wat is een 'Open TFTP Server'?</h2>

            <p>Trivial File Transfer Protocol, afgekort TFTP, is een eenvoudig
            bestandsoverdracht protocol dat veel gebruikt wordt om computers vanaf
            een netwerk te starten. Als de TCP/IP stack reeds draait kan TFTP ook
            gebruikt worden om andere apparatuur zoals routers, switches, ADSL- en
            kabelmodems van firmware en configuraties te voorzien. TFTP werd voor
            het eerst gedefinieerd in 1980.</p>

            <h2>Waarom is dit slecht?</h2>

            <p>TFTP heeft geen enkele vorm van authentication of encryptie. Dit maakt
            het erg eenvoudig voor iemand om al je configuratie bestanden te downloaden
            of een corrupte firmware te uploaden!</p>

            <h2>Advies</h2>

            <p>Er is geen reden om deze dienst op een publieke interface aan te bieden.
            Het advies is om deze dienst te stoppen of ervoor te zorgen dat het niet
            mogelijk is deze dienst via het internet te bereiken is. Dit kan door gebruik
            te maken van RFC1918 ip reeksen of een firewall.</p>

            ",
    ],

    'PHISING_WEBSITE' => [
        'name'        => 'Phishing Website',
        'description' => "
            <h2>What is a 'Phishing website'?</h2>

            <p>A Phishing website is the attempt to acquire sensitive information such as
            usernames, passwords, and credit card details (and sometimes, indirectly, money)
            by masquerading as a trustworthy entity in an electronic communication.
            Communications purporting to be from popular social web sites, auction sites, banks,
            online payment processors or IT administrators are commonly used to lure unsuspecting
            public. Phishing emails may contain links to websites that are infected with malware. </p>

            <h2>Why would this be bad?</h2>

            <p>A Phishing website is usually the result of a comprimised website or comprimised user
            credentials of that website.</p>

            <p>When your website is compromised, not only your website contains changes done by hackers
            but it also contains one or more security problems that allowed a hacker to gain access
            to your website in the first place. The compromised website provides a useful platform for
            a range of illicit activities.</p>

            <h2>Recommended action</h2>

            <p>If your site has been hacked or infected with malware, you should act quickly to repair the
            damage. First take your entire website offline first. This is pretty impopulair, however if
            you consider the fact your might be leaking privacy information or infecting the
            systems of your visitors you want to act as quickly as possible.</p>

            <p>After you closed your website you will need to clean up the changed area's of your
            website.</p>


            <h2>Tips how to resolve this matter</h2>

            <p>The safest way to clean the hacked website is to delete it entirely and then upload
            a known clean version of it.</p>

            <ul>
            <li>Look for files that have been changed recently or on times your developers are
            closed and not placing updates on the website<li>
            <li>Look in temporally folders for (executable) scripts</li>
            </ul>

            <p>In addition future risk of compromise can be reduced by following these basic tips: </p>

            <ul>
            <li>Keep software and all plug-ins updated. Whether you run popular content
            management software (e.g., WordPress, Joomla, Blogger) or custom software,
            make sure that software and all third party plug-ins or extensions are updated.
            Remove plug-i ns or other add-ons that aren’t in use</li>
            <li>Use strong, varied passwords. WordPress login credentials, for example,
            should be different from FTP credentials. Never store passwords on your local
            machine.</li>
            <li>Regularly scan your PC for malware and your website for unautherized changes</li>
            <li>Use appropriate file permissions on your web server.</li>
            <li>Research your options and make security a priority when choosing a web hosting
            provider. If you aren’t confident you can protect your site on your own, consider
            using an add-on security service from your hosting provider or a third party website
            security service.</li>
            </ul>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://www.google.com/webmasters/hacked/'>Google's help for webmasters of hacked websites</a><br>
            <a target'_blank' href='http://www.stopbadware.org/'>stopbadware.org has great information, and their forums have a number of helpful and knowledgeable volunteers who may be able to help.</a><br>
            <a target'_blank' href='http://www.antiphishing.org/'>The site antiphishing.org has recommendations on dealing with hacked sites.</a><br>

            ",
    ],

    'RBL_LISTED' => [
        'name'        => 'RBL Notatie',
        'description' => "
            <h2>What does 'RBL Listed' mean?</h2>

            <p>Your IP address has been listed on a RBL (real-time block list).</p>

            <p>This indicates that your server has probably been sending out a
            high volume of unsolicited email, your computer or server has been
            misconfigured or has been compromised</p>

            <h2>Why would this be bad?</h2>

            <p>Depending on the problem, your server can potentially be used by malicous
            users on the internet to send spam or do harm to others while using your
            server as a proxy.</p>

            <p>If your server remains listed on this RBL you will have trouble
            sending email to lots of recipients.</p>

            <h2>Recommended action</h2>

            <p>In the case of a misconfiguration it could for example be the case
            that your SMTP server is accepting email from untrusted sources and you
            will need to reconfigure your SMTP server.</p>

            <p>In the case of a compromise your server might have been hacked or infected
            by a trojan.</p>

            <p>If you have been accidently listed and have resolved the issue, you
            can request a delisting using the URL included in the ticket report. Do
            not attempt to request a delisting at a RBL without knowning for sure you
            have resolved the issue. If you request a delisting without solving the
            problem you might end up permanently listed on that RBL!</p>

            <h2>Tips to resolve this matter</h2>

            <p>Run a virus and/or malware scanner on the system to look for infections</p>

            <p>You also might want to check and resolve listings on other RBL's,
            using a RBL checker such as Anti-abuse multi-rbl-check</p>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://www.anti-abuse.org/multi-rbl-check'>Anti-abuse multi-rbl-check</a><br>

            ",
    ],

    'SPAM' => [
        'name'        => 'Spam',
        'description' => "
            <h2>What is 'Spam'?</h2>

            <p>A spam report indicates that one or more emails sent from
            your server have been flagged by an automated system or
            by an end recipient as spam or unsolicited email.</p>

            <h2>Why would this be bad?</h2>

            <p>Sending spam or unsolicited email is illegal and not allowed
            by our terms of service.</p>

            <h2>Recommended action</h2>

            <p>If you are indeed sending bulk email, please ensure you follow
            these bulk email rules to prevent your email from being flagged
            as spam:</p>

            <ul>
                <li>1) Your recipients have opted in for the service.</li>
                <li>2) Each email sent contains a working opt-out link.</li>
            </ul>

            <p>If you are not sending bulk email, then check your website and DNS
            for signs of comprimise. </p>

            <h2>Tips to resolve this matter</h2>

            <p>If the server should not be sending much or any email, this spam
            report could indicate that your server has been compromised.</p>

            <p>The best way to solve the problem is to stop your mailserver(MTA)
            and investigate the mail queues for SPAM messages. When you found an
            example you can open it to identify its source. By stopping the MTA
            you will prevent SPAM from being sent out. After solving the problem
            you must clean your mail queue from all these SPAM messages before
            starting your MTA again.</p>

            <h2>Getting more information</h2>

            <p>If you are unable to or unsure how to resolve this issue,
            please contact our abuse department for support.</p>

            ",
    ],

    'SPAMTRAP' => [
        'name'        => 'Spamval',
        'description' => "
            <h2>What is a SPAM Trap?</h2>

            <p>A SpamTrap is a honeypot used to collect spam.</p>

            <h2>Why would sending email to these this be bad?</h2>

            <p>SpamTraps are generally e-mail addresses that are made to recieve spam.
            SpamTraps are often hidden from view on websites, so that they are only harvested from automated scans.
            Since no legit e-mail is ever sent to this address, any e-mail's that are sent to the Spam-Traps are
            immediately considered unsolicited.</p>

            <h2>Recommended action</h2>

            <p>Clean up your mailing lists. Configure your 'Hard & Soft' Bounce values to
            correctly remove recipient's when needed.</p>

            <p>If you are not sending bulk email, then check your website and DNS
            for signs of comprimise. </p>

            <h2>Tips how to resolve this matter</h2>

            <p>When cleaning your mailing lists there are some thing that you can already do:</p>

            <ul>
                    <li>Remove malformed domain names</li>
                    <li>Remove role accounts (sales@example.com, accounts@example.net)</li>
                    <li>Adhere to the unsubscribe process</li>
                    <li>Never buy a mailing list</li>
                    <li>Use double opt-in</li>
            </ul>

            <p>The best way to solve the problem is to stop your mailserver(MTA)
            and investigate the mail queues for SPAM messages. When you found an
            example you can open it to identify its source. By stopping the MTA
            you will prevent SPAM from being sent out. After solving the problem
            you must clean your mail queue from all these SPAM messages before
            starting your MTA again.</p>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://blog.returnpath.com/blog/jamie-lawler/trap-tips-avoiding-and-removing-spam-traps'>Tips to avoid SpamTraps</a><br>
            <a target'_blank' href='http://www.activecampaign.com/help/bounces-soft-bounce-vs-hard-bounce/'>Hard/Soft Bounce configuration</a><br>

            ",
    ],

    'SSLV3_VULNERABLE_SERVER' => [
        'name'        => 'SSLv3 kwetsbare server',
        'description' => "
            <h2>What is a 'SSLv3 Vulnerable Server'?</h2>

            <p>POODLE (the 'Padding Oracle On Downgraded Legacy Encryption' attack) is the name for an
            OpenSSL bug. This bug impacts almost every system using any kind of encryption which is
            based on OpenSSL.</p>

            <h2>Why would this be bad?</h2>

            <p>The whole issue ultimately hinges on the site supporting SSLv3 and the attacker being
            able to downgrade the client to use SSLv3. These protocol downgrade attacks are old news
            and are still surfacing to cause problems. By simulating a failure during the negotiation
            process, an attacker can force a browser and a server to renegotiate using an older
            protocol, right back down to SSLv3. As the POODLE vulnerability is actually in the
            protocol itself, this isn't something that can be patched out like ShellShock and
            HeartBleed.</p>

            <p>The attack, specifically against the SSLv3 protocol, allows an attacker to obtain the
            plaintext of certain parts of an SSL connection, such as the cookie. Similar to BEAST, but
            more practical to carry out. Every server that is not patched for this bug is
            vulnarable for such attacks.</p>

            <h2>Recommended action</h2>

            <p>The easiest and most robust solution to POODLE is to disable SSLv3 support on your server.
            This does bring with it a couple of caveats though. For web traffic, there are some legacy
            systems out there that won't be able to connect with anything other than SSLv3. For example,
            systems using IE6 and Windows XP installations without SP3, will no longer be able to
            communicate with any site that ditches SSLv3. According to figures released by CloudFlare,
            who have completely disabled SSLv3 across their entire customer estate, only a tiny fraction
            of their web traffic will be affected as 98.88% of Windows XP users connect with TLSv1.0 or
            better.</p>

            <h2>Tips how to resolve this matter</h2>

            <h3>Nginx</h3>

            <p>ssl_protocols TLSv1 TLSv1.1 TLSv1.2;

            Similar to the Apache config above, you will get TLSv1.0+ support and no SSL. You can check the config and restart.

            sudo nginx -t

            sudo service nginx restart</p>

            <h3>IIS</h3>

            <p>This one requires some registry tweaks and a server reboot but still isn’t all that bad.
            Microsoft have a support article with the required information, but all you need to do is
            modify/create a registry DWORD value.

            HKey_Local_Machine\System\CurrentControlSet\Control\SecurityProviders \SCHANNEL\Protocols

            Inside protocols you will most likely have an SSL 2.0 key already, so create SSL 3.0
            alongside it if needed. Under that create a Server key and inside there a DWORD value called
            Enabled with value 0. Once that’s done reboot the server for the changes to take effect.</p>

            <h3>Apache</h3>

            <p>To disable SSLv3 on your Apache server you can configure it using the following.

            SSLProtocol All -SSLv2 -SSLv3

            This will give you support for TLSv1.0, TLSv1.1 and TLSv1.2, but explicitly removes support for SSLv2 and SSLv3. Check the config and then restart Apache.

            apachectl configtest

            sudo service apache2 restart</p>


            <p>After patching all the service(s) and confirming the bug is nog longer present on
            your system its highly recommended to get a NEW SSL certificate (including key, csr, etc)
            as it might have been comprised. Most SSL suppliers will issue such a certificate for free</p>

            <h2>Getting more information</h2>

            <a target'_blank' href='http://disablesslv3.com/'>Disable SSLv3 - a community-powered step-by-step tutorial</a><br>
            <a target'_blank' href='https://www.poodletest.com/'>SSLv3 / POODLE Test</a><br>

            ",
    ],

    'SPAMVERTISED_WEBSITE' => [
        'name'        => 'Spamvertised website',
        'description' => "
            <h2>What is a 'Spamvertised web site'?</h2>

            <p>A site that is being 'spamvertised' means that the site is being included as a link in spam emails.</p>

            <h2>Why is my website in Spam Emails?</h2>

            <p>This is generally the case when your site (or DNS) has been comprimised in some way.
            Spammers or hackers often add a redirect from your site to their Spam site.
            Spammers would prefer to use your website for their links, as most likely their site is already known as bad. </p>

            <h2>Recommended action</h2>

            <p>Check your website and DNS for signs of comprimise. </p>

            <h2>Tips on how to resolve this</h2>

            <ul>
            <li>If this is a CMS (WordPress, Drupal, Joomla etc), check the addons/plugins and update where possible.</li>
            <li>If this is a standard website, check for signs of infection or unknown links on webpages and take the nessesary action.</li>
            <li>If infection is found and resolved, begin to delist your URL on the relevant delist's.</li>
            </ul>

            <h2>Getting more information</h2>

            <a target'_blank' href='https://wordpress.org/plugins/sucuri-scanner/'>WordPress Security scanner (Sucuri)</a><br>
            <a target'_blank' href='http://spamvertised.abusebutler.com/'>List of Spamvertised websites</a><br>

            ",
    ],

    'OPEN_ELASTICSEARCH_SERVER' => [
        'name'        => 'Open ElasticSearch Server',
        'description' => "
            <h2>What is an 'Open ElasticSearch Server'?</h2>

            <p>Elasticsearch is a search server based on Lucene. It provides a distributed,
            multitenant-capable full-text search engine with a RESTful web interface and
            schema-free JSON documents.</p>

            <h2>Why would this be bad?</h2>

            <p>Your system has an ElasticSearch instance running (see www.elastic.co for
            more information) which is accessible on the internet. On its own, ElasticSearch
            does not support authentication or restrict access to the datastore, so it is
            possible that any entity that can access the ElasticSearch instance may have
            complete control.</p>

            <p>This is especially problematic if this instance has dynamic scripting running.
            The scripting engine can be abused to launch a denial of service attack.</p>

            <h2>Recommended action</h2>

            <p>Either bind this service only to non-public facing connections or add a firewall
            to block the port ElasticSearch is running on.</p>

            <h2>Tips to resolve this matter</h2>

            <p>Read the ElasticSearch documentation on how to properly secure your ElasticSearch
            instance.

            <h2>Getting more information</h2>

            <a href='http://bouk.co/blog/elasticsearch-rce/'>Insecure default in Elasticsearch enables remote code execution</a><br>
            ",
    ],

    'COPYRIGHT_INFRINGEMENT' => [
        'name'        => 'Copyright schending',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'POSSIBLE_DDOS_SENDING_SERVER' => [
        'name'        => 'Mogelijke DDoS Server',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'DDOS_SENDING_SERVER' => [
        'name'        => 'DDoS Server',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'OPEN_PORTMAP_SERVER' => [
        'name'        => 'Open Portmapper Server',
        'description' => "
            <h2>What is an 'Open Portmapper Server'?</h2>

            <p>The port mapper (rpc.portmap or rpcbind) is a remote procedure call (RPC) service
            running on TCP or UDP port 111 that runs on servers to provide information about
            running services and their corresponding port numbers, such as NFS.</p>

            <h2>Why would this be bad?</h2>

            <p>Once an attacker discovers an active port 111 on a device, he can use this information
            to learn about running services, which is a very important first step for a hacking attack.</p>

            <p>Additionally, hackers have also found this feature useful in performing a
            special type of DDoS attack called an 'Amplification Attack'.</p>

            <p>The attacker sends a packet apparently from the intended victim to some
            server on the Internet that will reply immediately. Because the source
            IP address is forged, the remote Internet server replies and sends data
            to the victim.</p>

            <p>That has two effects: the actual source of the attack is hidden and is
            very hard to trace, and, if many Internet servers are used, an attack
            can consist of an overwhelming number of packets hitting a victim from
            all over the world.</p>

            <p>But what makes reflection attacks really powerful is when they are
            also amplified: when a small forged packet elicits a large reply from
            the server (or servers). In that case, an attacker can send a small
            packet 'from' a forged source IP address and have the server (or
            servers) send large replies to the victim.</p>

            <p>Amplification attacks like that result in an attacker turning a small
            amount of bandwidth coming from a small number of machines into a massive
            traffic load hitting a victim from around the Internet.</p>

            <h2>Recommended action</h2>

            <p>We recommend you to only allow RPC calls from trusted sources. This
            can be achieved by dropping all traffic for RPC services on your local
            firewall and only allowing connections from trusted IP addresses.
            ",
    ],

    'MALWARE_INFECTION' => [
        'name'        => 'Malware infectie',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'COMMENT_SPAM' => [
        'name'        => 'Commentaar Spam',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'HACK_ATTACK' => [
        'name'        => 'Hack aanval',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'INFORMATIONAL' => [
        'name'        => 'Informationeel',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'LOGIN_ATTACK' => [
        'name'        => 'Login aanval',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'DICTIONARY_ATTACK' => [
        'name'        => 'Woordenboek aanval',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'RULE_BREAKER' => [
        'name'        => 'Regel breker',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'HAVE_I_BEEN_PWNED_DOMAIN_FOUND' => [
        'name'        => 'Have I been pwned breach',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_PROXY_SERVER' => [
        'name'        => 'Open proxy server',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_UBIQUITI_SERVER' => [
        'name'        => 'Open Ubiquiti server',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'BRUTE_FORCE_ATTACK' => [
        'name'        => 'Brute Force aanval',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'AMPLICATION_DDOS_VICTIM' => [
        'name'        => 'DDOS amplictifcatie slachtoffer',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'ACCESSIBLE_ADB_REPORT' => [
        'name'        => 'Open Android Debug Bridge',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'ACCESSIBLE_APPLE_REMOTE_DESKTOP_ARD_REPORT' => [
        'name'        => 'Open Apple Remote Desktop',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'CAIDA_IP_SPOOFER_REPORT' => [
        'name'        => 'CAIDA IP spoofer',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'DRONE_BOTNET_DRONE_REPORT' => [
        'name'        => 'Botnet Drone',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'NETCORE_NETIS_ROUTER_VULNERABILITY_SCAN_REPORT' => [
        'name'        => 'Netcore/Netis router kwetsbaar',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_DB2_DISCOVERY_SERVICE_REPORT' => [
        'name'        => 'Open DB2 discovery service ',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_MQTT' => [
        'name'        => 'OPEN MQTT service ',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_COAP' => [
        'name'        => 'Open COAP service',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_IPP' => [
        'name'        => 'Open IPP service',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_RADMIN' => [
        'name'        => 'Open RAdmin service',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_RDPEUDP' => [
        'name'        => 'Open Microsoft Remote Desktop Protocol service',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'OPEN_BASIC_AUTH_SERVICE' => [
        'name'        => 'Open Basic Authenticatie service',
        'description' => '
            This classification has no information text yet.
            ',
    ],
    'DARKNET' => [
        'name'        => 'Service contacting darknets',
        'description' => '
            This classification has no information text yet.
            ',
    ],

    'VULNERABLE_SMTP_SERVER' => [
        'name'          => 'Vulnerable SMTP server',
        'description'   => '
            This classification has no information text yet.
            ',
    ],

    'OPEN_AMQP' => [
        'name'          => 'Accessible Advanced Message Queuing Protocol (AMQP) server',
        'description'   => '
            This classification has no information text yet.
            ',
    ],
];
