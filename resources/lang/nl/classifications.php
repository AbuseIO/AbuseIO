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
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
'OPEN_SMARTINSTALL' => [
        'name'        => 'Open Cisco Smart Install',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'OPEN_HADOOP_SERVER' => [
        'name'        => 'Open Hadoop Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'OPEN_VNC_SERVER' => [
        'name'        => 'Open VNC Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'OPEN_SMB_SERVER' => [
        'name'        => 'Open SAMBA Server (SMB/CIFS)',
        'description' => 'Deze rapportage identificeert hosts die een SMB instance hebben draaien op poort 445/TCP die vrij toegankelijk via het internet is. 
        Deze dienst zou niet toegankelijk via het internet moeten zijn.',
    ],

    'OPEN_CWMP_SERVER' => [
        'name'        => 'Open CPE WAN Management Protocol (CWMP)',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'OPEN_TELNET_SERVER' => [
        'name'        => 'Open Telnet Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'OPEN_LDAP_SERVER' => [
        'name'        => 'Open LDAP Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'ISAKMP_VULNERABLE_DEVICE' => [
        'name'        => 'ISAKMP Vulnerable device',
        'description' => '
            <h2>Wat is een \'ISAKMP Vulnerable device\'?</h2>

            <p>  </p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Een kwetsbaarheid in Internet Key Exchange version 1 (IKEv1) packet processing code in Cisco IOS,
             Cisco IOS XE, en Cisco IOS XR Software zou een ongeauthentificeerde, remote aanvaller toe kunnen staan om de inhoud van 
             het geheugen op te halen, wat zou kunnen leiden tot het vrijgeven van vertrouwelijke informatie.</p>

            <h2>Aanbevolen actie</h2>

            <p>De enige manier om deze kwetsbaarheid op te lossen, is om IPSEC uit te zetten.</p>

            <h2>Tips om dit op te lossen/h2>
            <ul>
            <li>Upgrade naar een niet-getroffen versie van Cisco IOS</li>
            <li>implementeer een \'intrusion prevention system\' (IPS) of \'intrusion detection system\' (IDS) waarmee u aanvallen die gebruik maken van deze kwetsbaarheid kan voorkomen en detecteren.</li>
            </ul>

            <h2>Meer informatie</h2>

            <a target\'_blank\' href=\'https://sec.cloudapps.cisco.com/security/center/content/CiscoSecurityAdvisory/cisco-sa-20160916-ikev1\'>IKEv1 Information Disclosure Vulnerability in Multiple Cisco Products</a><br>

            ',
    ],

    'OPEN_RDP_SERVER' => [
        'name'        => 'Open RDP Server',
        'description' => '
            <h2>Wat is een \'Open RDP Server\'?</h2>

            <p>  </p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Dit rapport identificeert hosts die een Remote Desktop Protocol (RDP) dienst hebben draaien die voor een ieder vrij toegankelijk is via het internet.
            Verkeerd ingestelde RDP kan kwaadwillenden in staat stellen toegang te krijgen tot een desktop van een kwetsbare host.
            Dit maakt het mogelijk om informatie over de target host te bemachtigen, gezien de SSL -certificaten die door RDP worden gebruikt vaak de \'trivial hostname\' van het systeem bevatten.</p>

            <p>De kans is groot dat de server doelwit wordt van \'brute force\' aanvallen. Omdat de meeste RDP servers maar twee sessies per keer ondersteunen, wordt u dan buitengesloten.</p>

            <h2>Aanbevolen actie</h2>

            <p>Zet publieke toegang tot RDP uit of neem maatregelen om \'brute force attacks\' te detecteren en deze eventueel ook te mitigeren.</p>

            <h2>Tips om dit op te lossen</h2>
            <ul>
            <li>Firewall port TCP/3389 en gebruik een VPN om toegang te krijgen tot uw interne netwerk.</li>
            <li>Firewall port TCP/3389 en laat alleen vertrouwde IP-adressen toe.</li>
            </ul>

            <h2>Meer informatie</h2>

            <a target\'_blank\' href=\'https://technet.microsoft.com/en-us/library/cc743162(v=ws.11).aspx\'>Remote Desktop Services and Windows Firewall</a><br>
            ',
    ],
    
    'BOTNET_CONTROLLER' => [
        'name'        => 'Botnetserver',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'BOTNET_INFECTION' => [
        'name'        => 'Botnet-infectie',
        'description' => "
             <h2>Wat is een 'Botnet-infectie'?</h2>

            <p>Botnet is een porte-manteauwoord afgeleid van de woorden 'robot' en 'netwerk'. 
            Bot refereert aan een computerprogramma dat zelfstandig geautomatiseerde taken uitvoert.
            Zulke programma's kunnen voor legitieme doeleinden gebruikt worden, 
            zoekmachines gebruiken bijvoorbeeld bots om websites te indexeren. 
            Helaas kunnen bots ook voor malafide doeleinden worden misbruikt.
            Een botnet is een grote groep geïnfecteerde computers die via het internet met elkaar verbonden zijn.
            Criminelen die een botnet beheren, zorgen ervoor dat de programma's op zoveel mogelijk computers worden geïnstalleerd.
            De programma's blijven buiten zicht, draaien vaak op de achtergrond en zijn vaak lastig op te sporen door antivirusprogramma's.
            Zodra een computer is geïnfecteerd, kan het toegevoegd worden aan het botnet door het exploiteren van kwetsbare software aanwezig op het systeem.
            Er zijn veel manieren om geïnfecteerd te raken, waaronder het bezoeken van een (geïnfecteerde) website, 
            'drive-by-downloads', (malware die gedownloaded en geïnstalleerd wordt zonder het willen of weten van het slachtoffer), 
            het klikken op malafide bijlagen of links in e-mails of zelfs randapparatuur zoals (geïnfecteerde) usb-sticks of externe schijven op het systeem aansluiten.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Het IP-adres in de rapportage (het systeem dat erachter zit en gebruik maakt van NAT) is geïdentificeerd als deelnemend aan een botnet. 
            Gezien uw systeem aan het communiceren is met het botnet, kunt u er 99,9% zeker van zijn dat deze gecompromiteerd is. 
            Het systeem is geïnfeccteerd met malware en doet mee in een botnet.</p>

            <p>Een botnet kan worden gebruikt om uw persoonlijke data te stelen, 
            spam te versturen, andere computers te hacken en netwerkaanvallen te lanceren. 
            In al deze voorbeelden, bent u de bron van deze aanvallen!</p>

            <h2>Aanbevolen actie</h2>

            <p>Dit probleem moet worden opgelost door de kwaadaardige software te verwijderen.
            In het geval van zeer persistente infecties, zal het besturingssysteem opnieuw moeten worden
            geïnstalleerd om van de infectie af te kunnen komen.</p>

            <h2>Tips om dit op te lossen</h2>

            <p>Indien uw systeem een werkstation of server is:<ul>
            <li>Installeer en/of update een antivirussoftware en voer een volledige scan van uw computer uit. 
            Het wordt aangeraden om meerdere scanners te gebruiken, omdat niet alle scanners dezelfde soorten malware herkennen kunnen.</li>
            <li>Scan het netwerk voor gïnfecteerde bestanden. Vergeet niet ook uw backups te scannen, 
            alsmede alle computers die wellicht al een tijdje uit hebben gestaan ivm. bijv. vakanties.</li>
            <li>Gebruikers van enig systeem dat met een botnetinfectie te maken heeft, zullen al hun wachtwoorden zo snel mogelijk moeten veranderen.
            Dit houdt in: alle lokaal-opgeslagen wachtwoorden die toegang verschaffen tot andere systemen en applicaties 
            (inclusief zakelijke applicaties, toegang tot web beheerder-accounts, persoonlijke e-mail en sociale media, etc.).</li>
            <li>Als een computer met malware geïnfecteerd blijkt te zijn, is de kans groot dat er ook andere malware op het systeem aanwezig is. 
            Een 'clean reinstall' voorkomt dat deze vrij toegang tot het netwerk kunnen krijgen.</li>
            </ul></p>

            <p>Indien uw systeem een website / hostingsysteem is:<ul>
            <li>Als u het vermoeden heeft dat een gebruikersaccount gehackt is, wijzig onmiddelijk het wachtwoord van dit account.</li>
            <li>Scan alle gehoste websites en tijdelijke mappen voor verdachte bestanden en verwijder deze.
            Controleer ook uw lijst met processen (bijv. taakbeheer) voor mogelijke verdachte processen en beëindig deze.</li>
            <li>Controleer uw mailqeueus voor uitgaande SPAM en verwijder deze.</li>
            <li>Installeer een rootkit- en virusscanner om er zeker van te zijn dat alle malafide bestanden verwijderd zijn.</li>
            </ul></p>

            <p>Zodra alle malafide software verwijderd is, zorg ervoor dat de server niet opnieuw aangetast kan worden. 
            Installeer de nieuwste updates voor uw besturingssysteem, control panel en gehoste applicaties 
            inclusief thema's en plug-ins (zoals Wordpress) Als u deze niet direct upgraded, zal het systeem binnen korte tijd opnieuw geïnfecteerd zijn!
            </p>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://web.archive.org/web/20190526123321/https://www.ncsc.nl/binaries/content/documents/ncsc-en/current-topics/factsheets/release-me-from-a-botnet/1/Release%2Bme%2Bfrom%2Ba%2Bbotnet.pdf'>NCSC factsheet - Release me from a botnet (2012)</a><br>

            ",
    ],

    'COMPROMISED_SERVER' => [
        'name'        => 'Gehackte server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'COMPROMISED_WEBSITE' => [
        'name'        => 'Gecompromitteerde website',
        'description' => "
            <h2>Wat is een 'Compromised website'?</h2>

            <p>Een gecompromitteerde website betekent dat er (gehackte) content zonder toestemming op uw site is geplaatst. 
            Dit komt door kwetsbaarheden in de beveiliging van uw site.

            Malafide hackers zijn altijd op zoek naar nieuwe manieren, kwetsbaarheden, trucjes en 'social engineering' tactieken 
            die gebruikt kunnen worden om een site te compromiteren.
            Het is geen wonder dat de meeste website-eigenaren geen idee hebben hoe hun site überhaupt gehackt heeft kunnen worden.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Als uw site gehackt is, betekent dat niet alleen dat er ongewenste wijzigingen aan uw site zijn gedaan, 
            maar ook dat de site één of meer beveiligingsproblemen heeft die de hacker in staat stelde om in de eerste plaats in te kunnen breken.
            De gehackte site kan worden gebruikt voor een groot aantal ongewenste en/of malafide doeleinden, waaronder: </p>

            <ul>
            <li>Het hosten van malware - Dit omvat zowel complexe scripts die bezoekers hun PC infecteren, alsmede 
            phishing e-mails die een ontvanger hebben weten te overtuigen een bestand dat (ongewild) op uw website gehost is te downloaden. 
            In veel gevallen is zo'n malware(script) ergens in een subfolder verborgen.</li>
            <li>Geïnjecteerde content - Als hackers toegang tot uw website hebben, zouden ze kunnen proberen malafide content te injecteren. 
            Dit kan zowel direct op de site zelf gebeuren via javascript of in iframes. 'SQL injection' is ook een reëele dreiging.</li>
            <li>URL redirect - Duizende gehackte sites redirecten naar een handvol 'master URL's'. 
            Met een paar regels verborgen HTML wordt uw site in een soort 'voordeur' voor de badware veranderd. 
            De 'master URL's' kunnen bijvoorbeeld spammerige productpagina's of malware bevatten.</li>
            <li>Het hosten van o.a. phishing, spampagina's en/of pornografie - 
            één of meerdere statische pagina's op de gehackte site kunnen dienst doen om bijvoorbeeld 'spam products' (zoals geneesmiddelen, drugs, versterkende middelen, etc.) te adverteren.
            Ook neppagina's voor banken, betaaldiensten, e-maildiensten, etc. of het hosten van expliciet (soms illegaal) content vallen hieronder.</li>
            <li>Vandalisme - De hack kan zijn uitgevoerd om de eigenaar van de site te vernederen of bijvoorbeeld voor politieke doeleinden. 
            Dit valt onder de paraplu van het 'hacktivisme'. Sommige beheerders geven aan dat concurrenten dit ook doen. </li>
            <li>Overige content of activiteiten - Door de jaren heen zijn er aardig complexe vormen van misbruik geconstateerd. Bijvoorbeeld een script dat spam stuurt.</li>
            </ul>

            <h2>Aanbevolen actie</h2>

            <p>Als uw site met malware gehackt of geïnfecteerd is, haal eerst de site offline. 
            Dit is wellicht geen populaire maatregel, maar gezien er kans is dat uw site gegevens lekt of het systeem van uw bezoekers infecteert, 
            is het van belang zo snel mogelijk hierop te reageren om eventuele schade te beperken.</p>

            <p>Nadat u uw site offline heeft gezet, zult u de aaangetaste onderdelen van de site moeten opschonen.</p>

            <h2>Tips om dit op te lossen</h2>

            <p>De veiligste manier om een gehackte site op te schonen, is om hem helemaal te verwijderen
            en te vervangen met een eerdere versie waarvan men zeker weet dat hij niet geïnfecteerd is.</p>

            <ul>
            <li>Kijk of er bestanden zijn die recent zijn aangepast en/of op tijden waarop uw developers niet aan het werk waren.</li>
            <li>Kijk in tijdelijke mappen voor (uitvoerbare) scripts.</li>
            </ul>

            <p>Verder kan de kans op hercompromitering verkleint worden door de volgende tips op te volgen: </p>

            <ul>
            <li>Houd software en plug-ins up-to-date, ongeacht of u populaire content Management Softare (CMS) zoals 
            WordPress, Joomla of Blogger of iets anders gebruikt.
            Haal plug-ins die niet in gebruik zijn weg.</li>
            <li>Gebruik sterke en verschillende wachtwoorden. Uw WordPress inloggegevens zouden bijvoorbeeld anders moeten zijn dan uw FTP inloggegevens.
            Sla nooit wachtwoorden onbeveiligd op uw lokale machine op.</li>
            <li>Scan uw computer regelmatig voor malware en controleer uw website op ongewenste en/of onbevoegde veranderingen.</li>
            <li>Gebruik geschikte 'file permissions' op uw webserver.</li>
            <li>Laat beveiliging een prioriteit zijn bij het zoeken naar een webhoster. Als u niet zeker weet of u uw site zelf kunt beveiligen, 
            kijk of het een optie is om een beveiligingsservice af te nemen bij uw hosting provider 
            of een derde partij die zich in websitecybersecurity specialiseert.</li>
            </ul>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://web.dev/articles/hacked'>Google's tips voor webmasters van gehackte websites (Engels)</a><br>
            <a target'_blank' href='https://www.antiphishing.org/'>De site antiphishing.org heeft aanbevelingen over hoe men het beste om kan gaan met gehackte sites (Engels)</a><br>

            ",
    ],

    'DISTRIBUTION_WEBSITE' => [
        'name'        => 'Distributiewebsite',
        'description' => "
            <h2>Wat is een 'Distributiewebsite'?</h2>

            <p>Een distributiewebsie is een site die malware ter download aanbiedt. 
            Zie bijvoorbeeld downloadlinks verankerd in malafide documenten of executables. 
            Domeinen die hiervoor worden gebruikt, zijn vaak gehackte domeinen.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Als u malware op uw server hebt staan, stop hier onmiddelijk mee en verwijder het. Dit is namelijk illegaal. 
            Het kan ertoe leiden dat zoekmachine's uw site als verdacht gaan aanmerken, waardoor deze minder toegangkelijk voor uw bezoekers wordt.
            Als een hacker malware op uw site heeft gezet, dan is deze zeer waarschijnlijk gehackt.</p>

            <h2>Aanbevolen actie</h2>

            <p>Verwijder alle kwaadwillig gehoste bestanden, check of uw site gehackt is en ruim malafide bestanden op.</p>

            <h2>Tips om dit op te lossen</h2>
            <ul>
            <li>Indien dit om een CMS (WordPress, Drupal, Joomla, etc.) gaat, check of er updates voor uw add-ons en plugins beschikbaar zijn. Voer deze uit waar mogelijk.</li>
            <li>Indien dit een 'standaard' website is, controleer op tekeken van infectie of onbekende links op uw pagina's. Neem stappen om deze te verwijderen.</li>
            <li>Zodra het probleem is opgelost, vraag zoekmachines om uw site te herevalueren.</li>
            </ul>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://support.google.com/webmasters/topic/4598410?hl=en&ref_topic=4596795'>Google Webmaster tools for infected sites</a><br>
            <a target'_blank' href='http://www.bing.com/webmaster/help/malware-re-evaluation-e6982183'>Bing Reevaluation site</a><br>

            ",
    ],

    'FEEDBACK_LOOP' => [
        'name'        => 'Feedback Loop (FBL) bericht',
        'description' => "
                <h2>Wat is een Feedback Loop?</h2>

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

                <h2>Why is a Feedback Loop important?</h2>

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

                <h2>What can I do?</h2>

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

                <h2>But I am not sending these e-mails</h2>

                <p>If you receive a high number of FBL complaints on messages that you did not send yourself
                you should consider the host being reported on (e.g. the IP) to be comprimised as these e-mails
                did originate from your system. In these cases your advised to shutdown the mailserver and
                clean the system and its mailqueues before taking it online again.</p>

                <h2>What is questionable about Feedback Loops?</h2>

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
        'name'        => 'FREAK Vulnerable Server',
        'description' => "

            <h2>Wat is een 'FREAK Vulnerable Server'?</h2>

            <p>Servers die RSA_EXPORT ciphersuites accepteren, lopen de kans hun gebruikers bloot te stellen aan de zogenaamde FREAK-aanval.
            
            Servers that accept RSA_EXPORT cipher suites put their users at risk from the FREAK
            attack. Using Internet-wide scanning, we have been performing daily tests of all
            HTTPS servers at public IP addresses to determine whether they allow this weakened
            encryption. More than a third of all servers with browser-trusted certificates are
            at risk.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Servers die RSA_EXPORT ciphersuites accepteren, lopen de kans hun gebruikers bloot te stellen aan de zogenaamde FREAK-aanval.
            De FREAK-aanval is mogelijk wanneer een kwetsbare browser verbinding maakt met een webserver die de kwetsbare “export-grade” encryptie ondersteunt.
            
            Servers that accept RSA_EXPORT cipher suites put their users at risk from the FREAK
            attack. The FREAK attack is possible when a vulnerable browser connects to a
            susceptible web server — a server that accepts “export-grade” encryption.</p>

            <p>Deze kwetsbaarheid staat aanvallers toe HTTPS-verbindingen tussen kwetsbare clients en servers te onderscheppen 
            en deze te dwingen zwakkere encryptie te gebruiken. Deze is makkelijker te breken en staat de aanvaller toe (gevoelige) data te stelen of manipuleren. </p>

            <p>The factoring of such 512-bit export keys can be done with a cluster of EC2 virtual
            servers, and can be done in about 7.5 hours for $104 in EC2 time. The attacker
            factors the RSA modulus to recover the corresponding RSA decryption key. When the
            client encrypts the 'pre-master secret' to the server, the attacker can now decrypt
            it to recover the TLS 'master secret'. From here on out, the attacker sees plaintext
            and can inject anything it wants.</p>

            <h2>Aanbevolen actie</h2>

            <p>Zet onmiddelijk ondersteuning voor TLS export ciphersuites uit. Het is ook een goed idee om 
            andere kwetsbare ciphersuites uit te zetten en forward security aan te zetten. 
            Mozilla heeft instructies en een handige tool om geschikte SSL configuraties voor een groot aantal soorten webservers te genereren.
            Wij raden ook aan uw instellingen te testen met behulp van bijvoorbeeld de Qualys SSL Labsn SSL Server Test tool.
            
            You should immediately disable support for TLS export cipher suites. While you’re
            at it, you should also disable other cipher suites that are known to be insecure
            and enable forward secrecy. For instructions on how to secure popular HTTPS server
            software, we recommend Mozilla’s security configuration guide and their SSL configuration
            generator. We also recommend testing your configuration with the Qualys SSL Labs
            SSL Server Test tool</p>

            <h2>Tips om dit op te lossen</h2>

            <p>Indien u Apache gebruikt, voeg de volgende aan uw SSL configuratie toe:

            SSLCipherSuite ALL:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!MD5:!PSK</p>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://freakattack.com/'>Tracking the FREAK Attack</a><br>
            <a target'_blank' href='https://www.ssllabs.com/ssltest/'>SSL Server Testtool.</a><br>
            <a target'_blank' href='https://wiki.mozilla.org/Security/Server_Side_TLS#Recommended_configurations'>Mozilla’s security configuration guide</a><br>
            <a target'_blank' href='https://ssl-config.mozilla.org/'>SSL Configuration Generator</a><br>

            ",
    ],

    'HARVESTING' => [
        'name'        => 'Harvesting',
        'description' => "

            <h2>Wat is 'Harvesting'?</h2>

            <p>'E-mail harvesting' is het proces van met een aantal verschillende methoden 
            grote aantallen e-mailadressen en/of accounts te verzamelen voor gebruik in het versturen van bulk e-mail, 
            het versturen van spam of toegang tot systemen te verkrijgen.</p>

            <p>Een veelvoorkomende techniek is het gebruik van software die bekend staat als 'harvesting bots' of 'harvesters'.
            Deze programma's gaan webpagina's, mailing lists, internetfora, etc. af om openbaar toegankelijke e-mailadressen te verzamelen.</p>

            <p>Een andere methode genaamd 'directory harvest attack', werkt door verbinding te maken met een mailserver 
            en e-mailadressen te raden met gebruik van veelvoorkomende gebruikersnamen voor een bepaald domein.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Ongevraagd e-mail sturen naar ontvangstadressen verkregen via 'harvesting' is illegaal en is - per onze voorwaaarden -  niet toegestaan.</p>

            <h2>Aanbevolen actie</h2>

            <p>Indien u bewust e-mailadressen aan het harvesten bent, raden wij u sterk aan hiermee te stoppen om verdere escalatie te voorkomen.</p>

            <h2>Tips om dit op te lossen</h2>

            <p>Als u dit niet bewust doet, controleer of uw serrver en website(s) niet gehackt zijn.</p>

            <h2>Meer informatie</h2>

            ",
    ],

    'NOTICE_AND_TAKEDOWN_REQUEST' => [
        'name'        => 'Notice and Takedown verzoek',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'OPEN_CHARGEN_SERVER' => [
        'name'        => 'Open Chargen Server',
        'description' => "
            <h2>Wat is een 'Open Chargen server'?</h2>

            <p>Het Character Generator Protocol (CHARGEN) is een service bedoeld voor debuggen, 
            testen en meten. Het wordt zelden gebruikt, gezien de ontwerpfouten van het protocol makkelijk misbruik toestaan.
            Een host kan een verbinding met een server die CHARGEN ondersteunt via UDP of TCP poort 19 openen. 
            Zodra er een TCP-verbinding geopend is, zal de server willekeurige characters naar de verbonden host sturen 
            tot de host de verbinding sluit. 
            De UDP-implementatie is iets anders: 
            De server stuurt hierbij iedere keer dat het een datagram van de verbonden host ontvangt 
            een UDP datagram met een willekeurig aantal (tussen 0 en 512) karakters. 
            Alle data die de CHARGEN server ontvangt, wordt tevens weggegooid.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Een open (UDP) dienst draaien hoeft niet perse een probleem te zijn en is meestal een vereiste voor het installeren van een systeem. 
            Helaas misbruiken hackers deze dienst graag voor het uitvoeren van een bepaald type DDoS; de zogenaamde 'amplificatie’-aanval.</p>

            <p>Een amplificatie-aanval is alleen uit te voeren in combinatie met zogeheten 'reflection'.
            Dit is dat een aanvaller doet alsof diens IP-Adres dat van het slachtoffer is (spoofing). 
            Als de aanvaller geen reflection toe zou passen, dan zou die zichzelf namelijk aanvallen.</p>

            <p>De aanvaller stuurt een packet dat afkomstig lijkt te zijn van het slachtoffer naar een server die daar direct antwoord op geeft. 
            Omdat het IP-adres gespooft is, wordt de opgevraagde data naar het slachtoffer verzonden.</p>

            <p>Dit heeft twee gevolgen: allereerst, maakt dit de werkelijke bron van de aanval heel moeilijk te traceren. 
            Verder, indien er veel servers voor de aanval worden misbruikt, kan de aanval bestaan uit een overweldigend aantal packets afkomstig vanaf servers over de hele wereld verspreid.</p>

            <p>Reflection-aanvallen kunnen nog krachtiger zijn wanneer deze gecombineerd zijn met amplificatie; als een klein packet een groot antwoord krijgt van de server(s). 
            In dat geval stuurt de aanvaller een klein packet van een gespooft IP-adres waarna de server(s) een groot antwoord terug stuurt. </p>

            <p>Bij amplificatie-aanvallen zoals dat kunnen kwaadwillenden dus met een klein beetje bandbreedte van 
            een paar machines een grote hoeveelheid dataverkeer afkomstig vanaf het hele internet op een slachtoffer richten.</p>

            <h2>Aanbevolen actie</h2>

            <p>Er is geen reden om CHARGEN te draaien op een public-facing interface. 
            Zet deze uit of zorg ervoor dat deze niet vanaf het internet te bereiken is door RFC1918 spaces of een firewall in te zetten.</p>

            <h2>Meer informatie</h2>

            <a target'_blank' href='http://dnsamplificationattacks.blogspot.nl/2013/07/source-port-chargen-destination-port.html'>Amplification Attacks Observer</a><br>
            ",
    ],

    'OPEN_DNS_RESOLVER' => [
        'name'        => 'Open DNS Resolver',
        'description' => "
            <h2>Wat is een 'Open DNS Resolver'?</h2>

            <p>Een open DNS server is een DNS server die bereid is om recursieve DNS queries 
            voor een ieder op het Internet uit te voeren.</p>

            <p>Wanneer een DNS server een recursieve DNS query resolved, spoort het 
            informatie
            When a DNS server resolves a recursive DNS query, it tracks down
            information about a domain name hosted on some other DNS server
            somewhere else on the Internet (a recursive process involving
            several other DNS servers in the DNS hierarchy).</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Een open (UDP) dienst draaien hoeft niet perse een probleem te zijn en is meestal een vereiste voor het installeren van een systeem. 
            Helaas misbruiken hackers deze dienst graag voor het uitvoeren van een bepaald type DDoS; de zogenaamde 'amplificatie’-aanval.</p>

            <p>Een amplificatie-aanval is alleen uit te voeren in combinatie met zogeheten 'reflection'.
            Dit is dat een aanvaller doet alsof diens IP-Adres dat van het slachtoffer is (spoofing). 
            Als de aanvaller geen reflection toe zou passen, dan zou die zichzelf namelijk aanvallen.</p>

            <p>De aanvaller stuurt een packet dat afkomstig lijkt te zijn van het slachtoffer naar een server die daar direct antwoord op geeft. 
            Omdat het IP-adres gespooft is, wordt de opgevraagde data naar het slachtoffer verzonden.</p>

            <p>Dit heeft twee gevolgen: allereerst, maakt dit de werkelijke bron van de aanval heel moeilijk te traceren. 
            Verder, indien er veel servers voor de aanval worden misbruikt, kan de aanval bestaan uit een overweldigend aantal packets afkomstig vanaf servers over de hele wereld verspreid.</p>

            <p>Reflection aanvallen kunnen nog krachtiger zijn wanneer deze gecombineerd zijn met amplificatie; als een klein packet een groot antwoord krijgt van de server(s). 
            In dat geval stuurt de aanvaller een klein packet van een gespooft IP-adres waarna de server(s) een groot antwoord terug stuurt. </p>

            <p>Bij amplificatie-aanvallen zoals dat kunnen kwaadwillenden dus met een klein beetje bandbreedte van 
            een paar machines een grote hoeveelheid dataverkeer afkomstig vanaf het hele internet op een slachtoffer richten.</p>


            <h2>Aanbevolen actie</h2>

            <p>In most cases the computer has installed a DNS service as an dependancy
            as resolving is needed on that computer. However its only required to do
            local resolving, thus the added 'external access' to this service is
            unneeded. In such cases we recommend either changing the configuration
            of your DNS service or to firewall port 53 for external hosts.</p>

            <p>If you are running a DNS resolver for multiple computers, then consider
            to limit access to the required group of computer or implement safegaurds
            like 'Response Rate Limiting'(DNS-RRL) to prevent a DNS Amplification Attack.</p>

            <h2>Tips om dit op te lossen</h2>

            <h3>De DNS Service firewallen</h3>

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

            <p>Zie de volgende voorbeelden van Microsoft Learn (Engels):<br>
            <br>
            <a target'_blank' href='https://learn.microsoft.com/en-us/previous-versions/windows/it-pro/windows-server-2008-R2-and-2008/cc771738(v=ws.11)'>Disabling recursion on Windows Server 2008 R2 systems</a><br>
            <a target'_blank' href='https://learn.microsoft.com/en-us/previous-versions/windows/it-pro/windows-server-2003/cc787602(v=ws.10)'>Disabling recursion on older Windows Server systems</a><br>
            <a target'_blank' href='https://learn.microsoft.com/en-us/previous-versions/windows/it-pro/windows-server-2003/cc773370(v=ws.10)'>Acting as a non-recursive forwarder</a> (See the 'Notes' section under the 'Using the Windows interface' instructions)<br>
            </p>

            <h2>Meer informatie</h2>

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
            <h2>Wat is een 'Open mDNS Service'?</h2>

            <p>Een open mDNS server is een mDNS server die bereid is om recursieve DNS queries 
            voor een ieder op het Internet uit te voeren.</p>

            <p>When a DNS server resolves a recursive DNS query, it tracks down
            information about a domain name hosted on some other DNS server
            somewhere else on the Internet (a recursive process involving
            several other DNS servers in the DNS hierarchy).</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Een open (UDP) dienst draaien hoeft niet perse een probleem te zijn en is meestal een vereiste voor het installeren van een systeem. 
            Helaas misbruiken hackers deze dienst graag voor het uitvoeren van een bepaald type DDoS; de zogenaamde 'amplificatie’-aanval.</p>

            <p>Een amplificatie aanval is alleen uit te voeren in combinatie met zogeheten 'reflection'.
            Dit is dat een aanvaller doet alsof diens IP-Adres dat van het slachtoffer is (spoofing). 
            Als de aanvaller geen reflection toe zou passen, dan zou die zichzelf namelijk aanvallen.</p>

            <p>De aanvaller stuurt een packet dat afkomstig lijkt te zijn van het slachtoffer naar een server die daar direct antwoord op geeft. 
            Omdat het IP-adres gespooft is, wordt de opgevraagde data naar het slachtoffer verzonden.</p>

            <p>Dit heeft twee gevolgen: allereerst, maakt dit de werkelijke bron van de aanval heel moeilijk te traceren. 
            Verder, indien er veel servers voor de aanval worden misbruikt, kan de aanval bestaan uit een overweldigend aantal packets afkomstig vanaf servers over de hele wereld verspreid.</p>

            <p>Reflection-aanvallen kunnen nog krachtiger zijn wanneer deze gecombineerd zijn met amplificatie; als een klein packet een groot antwoord krijgt van de server(s). 
            In dat geval stuurt de aanvaller een klein packet van een gespooft IP-adres waarna de server(s) een groot antwoord terug stuurt. </p>

            <p>Bij amplificatie-aanvallen zoals dat kunnen kwaadwillenden dus met een klein beetje bandbreedte van 
            een paar machines een grote hoeveelheid dataverkeer afkomstig vanaf het hele internet op een slachtoffer richten.</p>


            <h2>Aanbevolen actie</h2>

            <p>Meestal heeft een computer een DNS-dienst geïnstalleerd als verieste omdat er geresolved moet worden.
            Dit hoeft enkel alleen maar lokaal, dus externe toegang tot deze dienst is onnodig.
            Indien dit het geval is, is het aan te raden om de configuratie van uw DNS-dienst aan te passen 
            of poort 5353 to firewallen zodat deze niet toegankelijk is voor externe hosts.

            
            In most cases the computer has installed a DNS service as an dependancy
            as resolving is needed on that computer. However its only required to do
            local resolving, thus the added 'external access' to this service is
            unneeded. In such cases we recommend either changing the configuration
            of your DNS service or to firewall port 5353 for external hosts.</p>

            <p>If you are running a DNS resolver for multiple computers, then consider
            to limit access to the required group of computer or implement safegaurds
            like 'Response Rate Limiting'(DNS-RRL) to prevent a DNS Amplification Attack.</p>

            <h2>Tips om dit op te lossen</h2>

            <h3>De DNS-dienst firewallen</h3>

            <p>Om inkomende remote requests te blokkeren, zal je moeten filteren op UDP/poort 5353. 
            Deze dienst kan ook luisteren op TCP/poort 5353, maar alleen UDP-diensten worden misbruikt voor DNS amplificatie-aanvallen.</p>

            ",
    ],

    'OPEN_IMPI_SERVER' => [
        'name'        => 'Open IPMI Server',
        'description' => "
            <h2>Wat is een 'Open IPMI Server'?</h2>

            <p>IPMI defines a set of interfaces used by system administrators for
            out-of-band management of computer systems and monitoring of their operation.
            For example, IPMI provides a way to manage a computer that may be powered off
            or otherwise unresponsive by using a network connection to the hardware
            rather than to an operating system or login shell.</p>

            <p>IPMI is integrated on most server systems, however under different names like
            iRMC, ILOM, BMC, iDRAC, etc. Each vender has their own implementation of IPMI,
            but the base is the same on each of them: It allows access to hardware outside
            your operating system (and its locally installed firewall!).</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>IPMI is the base of most of the Out Of Band / Lights Out management suites and
            is implemented by the server's Baseboard Management Controller (BMC). The BMC
            has near complete access and control of the server's resources, including, but
            not limited to, memory, power, and storage. Anyone that can control your BMC
            (via IPMI), can control your server.</p>

            <p>IPMI instances in general are known to contain a variety of vulnerabilities,
            some more serious than other. In short; you really do not want to expose IPMI
            to the internet. </p>

            <h2>Aanbevolen actie</h2>

            <p>Implement a seperate network for hosting these Out-Of-Band management entries and
            place them in a RFC1918(non public IP) space in combination with a VPN or add a
            hardware firewall in front of this network that filters access.</p>

            <p>Some IPMI implementations do offer some kind of what they call firewalling, however
            we havent come across an implementation that actually fully protects the IPMI
            interface from outside influance.</p>

            <h2>Meer informatie</h2>

            <a target'_blank' href='http://fish2.com/ipmi/'>Dan Farmer on IPMI security issues</a><br>
            <a target'_blank' href='https://www.us-cert.gov/ncas/alerts/TA13-207A'>US-CERT alert TA13-207A</a><br>

            ",
    ],

    'OPEN_MEMCACHED_SERVER' => [
        'name'        => 'Open Memcached Server',
        'description' => "
            <h2>Wat is een 'Open Memcached Server'?</h2>

            <p>Memcached is an in-memory key-value store for small chunks of
            arbitrary data (strings, objects) from results of database calls,
            API calls, or page rendering. Its intended for use in speeding up
            dynamic web applications by alleviating database load</p>

            <h2>Waarom is dit een probleem?</h2>

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

            <h2>Aanbevolen actie</h2>

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

            <h2>Meer informatie</h2>

            <atarget'_blank'  href='http://blog.codesherpas.com/on_the_path/2010/08/securing-memcache-in-2-minutes.html'>David Bock - Securing Memcache in 2 Minutes</a><br>

            ",
    ],

    'OPEN_MSSQL_SERVER' => [
        'name'        => 'Open Microsoft SQL Server',
        'description' => "
            <h2>Wat is een 'Open Microsoft SQL Server'?</h2>

            <p>Microsoft SQL Server is a relational database management system developed
            by Microsoft. As a database, it is a software product whose primary function
            is to store and retrieve data as requested by other software applications,
            be it those on the same computer or those running on another computer across
            a network (including the Internet). There are at least a dozen different
            editions of Microsoft SQL Server aimed at different audiences and for
            workloads ranging from small single-machine applications to large
            Internet-facing applications with many concurrent users. Its primary query
            languages are T-SQL and ANSI SQL.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>This service has the potential to expose information about a clients network
            on which this service is accessible and the service itself can be used in UDP
            amplification attacks. In addition it opens up your system to 0-day attacks or
            worm/virus infections that exploit a vulnarability in Windows to gain access
            to your system.</p>

            <h2>Aanbevolen actie</h2>

            <p>Either use the Windows Firewall or even better an external firewall to prevent
            access to Netbios (and other Windows ports). The windows firewall has an nasty
            way of trying to think for himself and for example automaticly starts to open
            ports if you install something that uses Netbios. In all cases the
            administrator is unaware of these open ports.</p>

            <h2>Tips om dit op te lossen</h2>

            <p>By default, Microsoft Windows enables the Windows Firewall, which closes port
            1433 to prevent Internet computers from connecting to a default instance of SQL
            Server on your computer. Connections to the default instance using TCP/IP are not
            possible unless you reopen port 1433. If you need access from remote machines to
            your Microsoft SQL server, then only allow the required hosts and close down
            world wide accesss to the SQL server.</p>

            <p>Ports used by Microsoft are: TCP/1433, UDP/1434, TCP/1434, TCP/4022, TCP/135, TCP/2383.
            In addition the SQL Server uses a randomly assign dynamic port for named instance! </p>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://msdn.microsoft.com/en-us/library/ms175043.aspx'>Configure a Windows Firewall for Database Engine Access</a><br>

            ",
    ],

    'OPEN_MONGODB_SERVER' => [
        'name'        => 'Open MongoDB Server',
        'description' => "
            <h2>Wat is een 'Open MongoDB Server'?</h2>

            <p>MongoDB is a cross-platform document-oriented database. Classified as a NoSQL
            database, MongoDB eschews the traditional table-based relational database
            structure in favor of JSON-like documents with dynamic schemas (MongoDB calls
            the format BSON), making the integration of data in certain types of
            applications easier and faster.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Your system has a MongoDB NoSQL database (see www.mongodb.org for more
            information) running which is accessible on the internet. While authentication
            is available for MongoDB, in many instances this authentication is not enabled.</p>

            <p>This enables an attacker, without circumventing any security measures, to get
            read-and-write access to these databases, many of which contain sensitive
            customer data or live backends of Web shops</p>

            <h2>Aanbevolen actie</h2>

            <p>Either bind this service only to non-public facing connections or add a firewall
            to block the port MongoDB is running on.</p>

            <h2>Tips om dit op te lossen</h2>

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

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://web.archive.org/web/20160304041647/https://cispa.saarland/wp-content/uploads/2015/02/MongoDB_documentation.pdf'>MongoDB databases at risk (2015)</a><br>

            ",
    ],

    'OPEN_NATPMP_SERVER' => [
        'name'        => 'Open NAT_PMP Server',
        'description' => "
            <h2>Wat is een 'Open NAT-PMP Server'?</h2>

            <p>The NAT Port Mapping Protocol (NAT-PMP) is a network protocol for establishing
            network address translation (NAT) settings and port forwarding configurations
            automatically without user effort. The protocol automatically determines the
            external IPv4 address of a NAT gateway, and provides means for an application
            to communicate the parameters for communication to peers.</p>

            <h2>Waarom is dit een probleem?</h2>

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

            <h2>Aanbevolen actie</h2>

            <p>Developers and administrators implementing NAT-PMP should exercise care to ensure
            that devices are configured securely, specifically that</p>

            - the LAN and WAN interfaces are correctly assigned,
            - NAT-PMP requests are only accepted on internal interfaces, and
            - port mappings are only opened for the requesting internal IP address.

            <h2>Tips om dit op te lossen</h2>

            - Deploy firewall rules to block untrusted hosts from being able to access port 5351/udp.
            - Consider disabling NAT-PMP on the device if it is not absolutely necessary.

            <h2>Meer informatie</h2>

            <a target'_blank' href='http://www.kb.cert.org/vuls/id/184540'>Incorrect implementation of NAT-PMP in multiple devices</a><br>
            <a target'_blank' href='https://community.rapid7.com/community/metasploit/blog/2014/10/21/r7-2014-17-nat-pmp-implementation-and-configuration-vulnerabilities'>NAT-PMP Implementation and Configuration Vulnerabilities</a>

            ",
    ],

    'OPEN_NTP_SERVER' => [
        'name'        => 'Open NTP Server',
        'description' => "
            <h2>Wat is een 'Open NTP Server'?</h2>

            <p>Network Time Protocol (NTP) is een netwerkprotocol voor tijdsynchronisatie
            tussen computersystemen over packet-switched variable-lancy datanetwerken. 
            NTP werdt al vóór 1985 gebruikt, wat het één van de oudste internetprotocollen nog in gebruik maakt.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Een open (UDP) dienst draaien hoeft niet perse een probleem te zijn en is meestal een vereiste voor het installeren van een systeem. 
            Helaas misbruiken hackers deze dienst graag voor het uitvoeren van een bepaald type DDoS; de zogenaamde 'amplificatie’-aanval.</p>

            <p>Een amplificatie-aanval is alleen uit te voeren in combinatie met zogeheten 'reflection'.
            Dit is dat een aanvaller doet alsof diens IP-Adres dat van het slachtoffer is (spoofing). 
            Als de aanvaller geen reflection toe zou passen, dan zou die zichzelf namelijk aanvallen.</p>

            <p>De aanvaller stuurt een packet dat afkomstig lijkt te zijn van het slachtoffer naar een server die daar direct antwoord op geeft. 
            Omdat het IP-adres gespooft is, wordt de opgevraagde data naar het slachtoffer verzonden.</p>

            <p>Dit heeft twee gevolgen: allereerst, maakt dit de werkelijke bron van de aanval heel moeilijk te traceren. 
            Verder, indien er veel servers voor de aanval worden misbruikt, kan de aanval bestaan uit een overweldigend aantal packets afkomstig vanaf servers over de hele wereld verspreid.</p>

            <p>Reflection-aanvallen kunnen nog krachtiger zijn wanneer deze gecombineerd zijn met amplificatie; als een klein packet een groot antwoord krijgt van de server(s). 
            In dat geval stuurt de aanvaller een klein packet van een gespooft IP-adres waarna de server(s) een groot antwoord terug stuurt. </p>

            <p>Bij amplificatie-aanvallen zoals dat kunnen kwaadwillenden dus met een klein beetje bandbreedte van 
            een paar machines een grote hoeveelheid dataverkeer afkomstig vanaf het hele internet op een slachtoffer richten.</p>


            <h2>Aanbevolen actie</h2>

            <p>In most cases the computer has installed a NTP service as an dependancy
            as timekeeping is needed on that computer. However its only required to do
            local timekeeping, thus the added 'external access' to this service is
            unneeded. In such cases we recommend either changing the configuration
            of your NTP service or to firewall port 123 for external hosts.</p>

            <p>If you are running a NTP Server for multiple computers, then consider
            to limit access to the required group of computer or implement safegaurds
            like ... to prevent a DNS Amplification Attack.</p>


            <h2>Tips om dit op te lossen</h2>

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


            <h2>Meer informatie</h2>

            <a target'_blank' href='http://www.team-cymru.org/secure-ntp-template.html'>Examples in securing a NTP service</a>
            <a target'_blank' href='http://www.internetsociety.org/sites/default/files/01_5.pdf'>Amplification Hell: Revisiting Network Protocols for DDoS Abuse</a>

            ",
    ],

    'OPEN_NETBIOS_SERVER' => [
        'name'        => 'Open Netbios Server',
        'description' => "
            <h2>Wat is een 'Open Netbios server'?</h2>

            <p>NetBIOS is a transport protocol that Microsoft Windows systems use to share
            resources.  For example, if a PC running Windows wants to connect to and access a
            share on a file server, it probably uses NetBIOS.  There have been some changes in
            recent days, however, that allow this connection without it. SMB, the method used
            to access file and printer shares, can also run independently of NetBIOS over TCP
            ports 139 and 445.  Both of these approaches, however, tend to increase the attack
            surface of a network.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>The ports that that are open to the Internet are UDP/137, UDP/138, and TCP/139.
            Unfortunately, the most popular attacker target is NetBIOS and against these
            ports.</p>

            <p>Once an attacker discovers an active port 139 on a device, he can run NBSTAT to
            begin the very important first step of an attack—footprinting.  With the NBSTAT
            command, he can obtain some or all of the following information:</p>

            <ul>
            <li>Computernaam</li>
            <li>Inhoud van de remote name cache, inclusief IP-addressen</li>
            <li>Een lijst met lokale NetBIOS namen</li>
            <li>Een lijst met namen geresolved via broadast of WINSA</li>
            <li>Inhoud van de session table met de IP-adressen van de bestemmming</li>
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

            <h2>Aanbevolen actie</h2>

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

            <h2>Tips om dit op te lossen</h2>

            <p>In a privileged DOS box run the following commands:</p>

            netsh advfirewall firewall add rule name='NetBIOS UDP Port 137' dir=in action=deny protocol=UDP localport=137<br>
            netsh advfirewall firewall add rule name='NetBIOS UDP Port 137' dir=out action=deny protocol=UDP localport=137<br>
            netsh advfirewall firewall add rule name='NetBIOS UDP Port 138' dir=in action=deny protocol=UDP localport=138<br>
            netsh advfirewall firewall add rule name='NetBIOS UDP Port 138' dir=out action=deny protocol=UDP localport=138<br>
            netsh advfirewall firewall add rule name='NetBIOS TCP Port 139' dir=in action=deny protocol=TCP localport=139<br>
            netsh advfirewall firewall add rule name='NetBIOS TCP Port 139' dir=out action=deny protocol=TCP localport=139<br>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://technet.microsoft.com/en-us/library/cc940063.aspx'>Microsoft NetBIOS Over TCP/IP guide</a>

            ",
    ],

    'OPEN_QOTD_SERVER' => [
        'name'        => 'Open QOTD Server',
        'description' => "
            <h2>Wat is een 'Open QOTD Server'?</h2>

            <p>The Quote Of The Day (QOTD) service is a member of the Internet protocol
            suite, defined in RFC 865. As indicated there, the QOTD concept predated
            the specification, when QOTD was used by mainframe sysadmins to broadcast
            a daily quote on request by a user. It was then formally codified both
            for prior purposes as well as for testing and measurement purposes in RFC 865.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Een open (UDP) dienst draaien hoeft niet perse een probleem te zijn en is meestal een vereiste voor het installeren van een systeem. 
            Helaas misbruiken hackers deze dienst graag voor het uitvoeren van een bepaald type DDoS; de zogenaamde 'amplificatie’ aanval.</p>

            <p>Een amplificatie aanval is alleen uit te voeren in combinatie met zogeheten 'reflection'.
            Dit is dat een aanvaller doet alsof diens IP-Adres dat van het slachtoffer is (spoofing). 
            Als de aanvaller geen reflection toe zou passen, dan zou die zichzelf namelijk aanvallen.</p>

            <p>De aanvaller stuurt een packet dat afkomstig lijkt te zijn van het slachtoffer naar een server die daar direct antwoord op geeft. 
            Omdat het IP-adres gespooft is, wordt de opgevraagde data naar het slachtoffer verzonden.</p>

            <p>Dit heeft twee gevolgen: allereerst, maakt dit de werkelijke bron van de aanval heel moeilijk te traceren. 
            Verder, indien er veel servers voor de aanval worden misbruikt, kan de aanval bestaan uit een overweldigend aantal packets afkomstig vanaf servers over de hele wereld verspreid.</p>

            <p>Reflection aanvallen kunnen nog krachtiger zijn wanneer deze gecombineerd zijn met amplification; als een klein packet een groot antwoord krijgt van de server(s). 
            In dat geval stuurt de aanvaller een klein packet van een gespooft IP-adres waarna de server(s) een groot antwoord terug stuurt. </p>

            <p>Bij amplificatie-aanvallen zoals dat kunnen kwaadwillenden dus met een klein beetje bandbreedte van 
            een paar machines een grote hoeveelheid dataverkeer afkomstig vanaf het hele internet op een slachtoffer richten.</p>

            <h2>Aanbevolen actie</h2>

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
            <h2>Wat is een 'Open REDIS Server'?</h2>

            <p>Redis clients communicate with the Redis server using a protocol called
            RESP (REdis Serialization Protocol). While the protocol was designed
            specifically for Redis, it can be used for other client-server software
            projects.</p>

            <h2>Waarom is dit een probleem?</h2>

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

            <h2>Aanbevolen actie</h2>

            <p>Access to the Redis port should be denied to everybody but trusted clients in
            the network, so the servers running Redis should be directly accessible only
            by the computers implementing the application using Redis.</p>

            <p>In the common case of a single computer directly exposed to the internet, such
            as a virtualized Linux instance (Linode, EC2, ...), the Redis port should be
            firewalled to prevent access from the outside. Clients will still be able to
            access Redis using the loopback interface.</p>

            <p></p>

            <h2>Tips om dit op te lossen</h2>

            <h3>Remote access firewallen</h3>

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


            <h2>Meer informatie</h2>

            <a target'_blank' href='http://redis.io/topics/security'>Redis Security advisory</a><br>

            ",
    ],

    'OPEN_SNMP_SERVER' => [
        'name'        => 'Open SNMP Server',
        'description' => "
            <h2>Wat is een 'Open SNMP Server'?</h2>

            <p>Simple Network Management Protocol (SNMP) is a popular protocol for network
            management. It is used for collecting information from, and configuring,
            network devices, such as servers, printers, hubs, switches, and routers on an
            Internet Protocol (IP) network.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Open SNMP Servers can be used to collect privileged information from the
            system or even to write new 'settings' to the system if not correctly
            configured.</p>

            <p>Verder hoeft een open (UDP) dienst draaien niet perse een probleem te zijn en is meestal een vereiste voor het installeren van een systeem. 
            Helaas misbruiken hackers deze dienst graag voor het uitvoeren van een bepaald type DDoS; de zogenaamde 'amplificatie’ aanval.</p>

            <p>Een amplificatie aanval is alleen uit te voeren in combinatie met zogeheten 'reflection'.
            Dit is dat een aanvaller doet alsof diens IP-Adres dat van het slachtoffer is (spoofing). 
            Als de aanvaller geen reflection toe zou passen, dan zou die zichzelf namelijk aanvallen.</p>

            <p>De aanvaller stuurt een packet dat afkomstig lijkt te zijn van het slachtoffer naar een server die daar direct antwoord op geeft. 
            Omdat het IP-adres gespooft is, wordt de opgevraagde data naar het slachtoffer verzonden.</p>

            <p>Dit heeft twee gevolgen: allereerst, maakt dit de werkelijke bron van de aanval heel moeilijk te traceren. 
            Verder, indien er veel servers voor de aanval worden misbruikt, kan de aanval bestaan uit een overweldigend aantal packets afkomstig vanaf servers over de hele wereld verspreid.</p>

            <p>Reflection aanvallen kunnen nog krachtiger zijn wanneer deze gecombineerd zijn met amplification; als een klein packet een groot antwoord krijgt van de server(s). 
            In dat geval stuurt de aanvaller een klein packet van een gespooft IP-adres waarna de server(s) een groot antwoord terug stuurt. </p>

            <p>Bij amplificatie-aanvallen zoals dat kunnen kwaadwillenden dus met een klein beetje bandbreedte van 
            een paar machines een grote hoeveelheid dataverkeer afkomstig vanaf het hele internet op een slachtoffer richten.</p>


            <h2>Aanbevolen actie</h2>

            <ul>
            <li>Use firewalling to block UDP/161 entirely or only allow the hosts that
            need access to this service</li>
            <li>Update the SNMP configuration to use a different community string then public. Something
            strong like ThisIsMyCommunityString</li>
            <li>Update the SNMP configuration to use a host based ACL's in combination with either the 'public'
            community or a string thats more 'secure'</li>
            </ul>

            <h2>Tips om dit op te lossen</h2>

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

            <h2>Meer informatie</h2>

            ",
    ],

    'OPEN_SSDP_SERVER' => [
        'name'        => 'Open SSDP Server',
        'description' => "
            <h2>Wat is een 'Open SSDP Server'?</h2>

            <p>The Simple Service Discovery Protocol (SSDP) is a network protocol
            based on the Internet Protocol Suite for advertisement and discovery of
            network services and presence information. It accomplishes this without
            assistance of server-based configuration mechanisms, such as the Dynamic
            Host Configuration Protocol (DHCP) or the Domain Name System (DNS), and
            without special static configuration of a network host. SSDP is the basis
            of the discovery protocol of Universal Plug and Play (UPnP) and is
            intended for use in residential or small office environments.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Een open (UDP) dienst draaien hoeft niet perse een probleem te zijn en is meestal een vereiste voor het installeren van een systeem. 
            Helaas misbruiken hackers deze dienst graag voor het uitvoeren van een bepaald type DDoS; de zogenaamde 'amplificatie’ aanval.</p>

            <p>Een amplificatie aanval is alleen uit te voeren in combinatie met zogeheten 'reflection'.
            Dit is dat een aanvaller doet alsof diens IP-Adres dat van het slachtoffer is (spoofing). 
            Als de aanvaller geen reflection toe zou passen, dan zou die zichzelf namelijk aanvallen.</p>

            <p>De aanvaller stuurt een packet dat afkomstig lijkt te zijn van het slachtoffer naar een server die daar direct antwoord op geeft. 
            Omdat het IP-adres gespooft is, wordt de opgevraagde data naar het slachtoffer verzonden.</p>

            <p>Dit heeft twee gevolgen: allereerst, maakt dit de werkelijke bron van de aanval heel moeilijk te traceren. 
            Verder, indien er veel servers voor de aanval worden misbruikt, kan de aanval bestaan uit een overweldigend aantal packets afkomstig vanaf servers over de hele wereld verspreid.</p>

            <p>Reflection aanvallen kunnen nog krachtiger zijn wanneer deze gecombineerd zijn met amplification; als een klein packet een groot antwoord krijgt van de server(s). 
            In dat geval stuurt de aanvaller een klein packet van een gespooft IP-adres waarna de server(s) een groot antwoord terug stuurt. </p>

            <p>Bij amplificatie-aanvallen zoals dat kunnen kwaadwillenden dus met een klein beetje bandbreedte van 
            een paar machines een grote hoeveelheid dataverkeer afkomstig vanaf het hele internet op een slachtoffer richten.</p>


            <h2>Aanbevolen actie</h2>

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
            bestandsoverdrachtprotocol dat veel gebruikt wordt om computers vanaf
            een netwerk op te starten. Als de TCP/IP stack reeds draait kan TFTP ook
            gebruikt worden om andere apparatuur zoals routers, switches, ADSL- en
            kabelmodems van firmware en configuraties te voorzien. TFTP werd voor
            het eerst gedefinieerd in 1980.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>TFTP heeft geen enkele vorm van authentificatie noch encryptie. Dit maakt
            het erg eenvoudig voor iemand om al je configuratiebestanden te downloaden
            of corrupte firmware te uploaden!</p>

            <h2>Advies</h2>

            <p>Er is geen reden om deze dienst op een publieke interface aan te bieden.
            Het advies is om deze dienst te stoppen of ervoor te zorgen dat het niet
            mogelijk is deze dienst via het internet te kunnen bereiken. Dit kan door gebruik
            te maken van RFC1918 ip-reeksen of een firewall.</p>

            ",
    ],

    'PHISING_WEBSITE' => [
        'name'        => 'Phishing Website',
        'description' => "
            <h2>Wat is een 'Phishing website'?</h2>

            <p>Een phishingwebsite is een site die wordt gebruikt om gevoelige informatie zoals 
            gebruikersnamen, wachtwoorden, bankiergegevens (en soms indirect: geld) door zich voor te doen als een vertrouwde website. 
            Mails en communicatie die claimen van o.a. populaire sociale media sites, veilingsites, 
            banken, online betaaldiensten, beroemdheden of ICT-beheerders af te komen, 
            worden ingezet om mensen naar deze sites te krijgen en hun gegevens daarheen te sturen. Deze sites kunnen tevens geïnfecteerd met malware zijn. </p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Een phishingwebsite is vaak het resultaat van een gehackte website of gecompromitteerde inloggegevens van een gebruiker van deze site.</p>

            <p>Als uw site gehackt is, betekent dat niet alleen dat er ongewenste wijzigingen aan uw site zijn gedaan, 
            maar ook dat de site één of meer beveiligingsproblemen heeft die de hacker in staat stelde om in de eerste plaats in te kunnen breken.
            De gehackte site kan worden gebruikt voor een groot aantal ongewenste en/of malafide doeleinden.</p>

            <h2>Aanbevolen actie</h2>

            <p>Als uw site met malware gehackt of geïnfecteerd is, haal eerst de site offline. 
            Dit is wellicht geen populaire maatregel, maar gezien er kans is dat uw site gegevens lekt of het systeem van uw bezoekers infecteert, 
            is het van belang zo snel mogelijk hierop te reageren om eventuele schade te beperken.</p>

            <p>Nadat u uw site offline heeft gezet, zult u de aaangetaste onderdelen van de site moeten opschonen.</p>


            <h2>Tips om dit op te lossen</h2>

            <p>De veiligste manier om een gehackte site op te schonen, is om hem helemaal te verwijderen
            en te vervangen met een eerdere versie waarvan men zeker weet dat hij niet geïnfecteerd is.</p>

            <ul>
            <li>Kijk of er bestanden zijn die recent zijn aangepast en/of op tijden waarop uw developers niet aan het werk waren.</li>
            <li>Kijk in tijdelijke mappen voor (uitvoerbare) scripts.</li>
            </ul>

            <p>Verder kan de kans op hercompromitering verkleint worden door de volgende tips op te volgen: </p>

            <ul>
            <li>Houd software en plug-ins up-to-date, ongeacht of u populaire content Management Softare (CMS) zoals 
            WordPress, Joomla of Blogger of iets anders gebruikt.
            Haal plug-ins die niet in gebruik zijn weg.</li>
            <li>Gebruik sterke en verschillende wachtwoorden. Uw WordPress inloggegevens zouden bijvoorbeeld anders moeten zijn dan uw FTP inloggegevens.
            Sla nooit wachtwoorden onbeveiligd op uw lokale machine op.</li>
            <li>Scan uw computer regelmatig voor malware en controleer uw website op ongewenste en/of onbevoegde veranderingen.</li>
            <li>eGbruik geschikte 'file permissions' op uw webserver.</li>
            <li>Laat beveiliging een prioriteit zijn bij het zoeken naar een webhoster. Als u niet zeker weet of u uw site zelf kunt beveiligen, 
            kijk of het een optie is om een beveiligingsservice af te nemen bij uw hosting provider 
            of een derde partij die zich in websitecybersecurity specialiseert.</li>
            </ul>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://web.dev/articles/hacked'>Google's tips voor webmasters van gehackte websites (Engels)</a><br>
            <a target'_blank' href='https://www.antiphishing.org/'>De site antiphishing.org heeft aanbevelingen over hoe men het beste om kan gaan met gehackte sites (Engels)</a><br>

            ",
    ],

    'RBL_LISTED' => [
        'name'        => 'RBL Listed',
        'description' => "
            <h2>Wat betekent 'RBL Listed'?</h2>

            <p>Uw IP-address staat op een RBL (real-time block list).</p>

            <p>This indicates that your server has probably been sending out a
            high volume of unsolicited email, your computer or server has been
            misconfigured or has been compromised</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Depending on the problem, your server can potentially be used by malicous
            users on the internet to send spam or do harm to others while using your
            server as a proxy.</p>

            <p>If your server remains listed on this RBL you will have trouble
            sending email to lots of recipients.</p>

            <h2>Aanbevolen actie</h2>

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

            <p>Check uw systeem voor tekenen van infectie door een virus- en/of malwarescan uit te voeren.</p>

            <p>You also might want to check and resolve listings on other RBL's,
            using a RBL checker such as Anti-abuse multi-rbl-check</p>

            <h2>Meer informatie</h2>

            <a target'_blank' href='http://www.anti-abuse.org/multi-rbl-check'>Anti-abuse multi-rbl-check</a><br>

            ",
    ],

    'SPAM' => [
        'name'        => 'Spam',
        'description' => "
            <h2>Wat is 'Spam'?</h2>

            <p>Als u een spamrapportge ontvangt, betekent dat dat één of 
            meerdere e-mails die vanaf uw server verzonden worden, door een automatisch systeem 
            en/of een ontvanger zijn gemarkeert als spam of ongewenste e-mail.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Spam of ongewenste e-mail versturen is illegaal en -per onze voorwaarden - niet toegestaan.</p>

            <h2>Aanbevolen actie</h2>

            <p>Als u inderdaad bulk e-mail verstuurd, volg de onderstaande regels 
            voor bulk mail om te voorkomen dat uw mails als spam worden aangemerkt:</p>

            <ul>
                <li>1) De ontvangers van uw mails hebben zich aangemeld voor deze dienst.</li>
                <li>2) Elke e-mail bevat een afmeldlink.</li>
            </ul>

            <p>Als u geen bulk e-mail verstuurd, controleer uw website en DNS op tekenen van compromittering. </p>

            <h2>Tips om dit op te lossen</h2>

            <p>Als het niet de bedoeling is dat de server (grote hoeveelheden) 
            mail verstuurt, kan dit betekenen dat de server gehackt is.</p>

            <p>De beste manier om dit probleem op te lossen, is om uw mailserver (MTA)
            stop te zetten en de mailqueue op SPAM-berichten te controleren. 
            Als u verdachte mail vindt, kunt u deze openen om er achter te komen waar deze vandaan komt. 
            Door de MTA stop te zetten, voorkomt u dat de SPAM wordt verstuurd. 
            Nadat het probleem is opgelost, moet u alle SPAM uit de mailqueue halen alvorens u uw MTA opnieuw opstart.</p>

            <h2>Meer informatie/h2>

            <p>Neem contact op met onze abuse-afdeling als u niet weet hoe u dit probleem op kan lossen.</p>

            ",
    ],

    'SPAMTRAP' => [
        'name'        => 'Spam Trap',
        'description' => "
            <h2>Wat is een SPAM Trap?</h2>

            <p>Een SpamTrap is een honeypot die gebruikt wordt om spam te verzamelen.</p>

            <h2>Waarom is het een probleem als hier mails naartoe worden verstuurd?</h2>

            <p>SpamTraps zijn e-mailadressen die speciaal bedoelt zijn om spam te ontvangen.
            SpamTraps worden voor het oog verborgen op websites, waardoor ze alleen door geautomatiseerde scanners worden gezien.
            Gezien er geen legitieme mail naar dit e-mailadres wordt gestuurd, worden alle ontvangen mails direct als ongewenst aangezien.</p>

            <h2>Aanbevolen actie</h2>

            <p>Schoon uw mailinglijst op. Configureer uw 'Hard & Soft' Bounce waardes om ontvangers correct te verwijderen indien nodig.v</p>

            <p>Als u geen bulk e-mail verstuurd, check dan uw website en DNS voor tekenen van compromittering. </p>

            <h2>Tips om dit op te lossen</h2>

            <p>Er zijn een aantal dingen die u doen kan tijdens het opruimen van uw mailinglist:</p>

            <ul>
                    <li>Haal misvormde domeinnamen weg</li>
                    <li>Haal rolaccounts eruit (sales@example.com, accounts@example.net)</li>
                    <li>Houd u aan het 'unsubscribe process'</li>
                    <li>Koopt nooit een mailing list</li>
                    <li>Gebruik 'double opt-in'</li>
            </ul>

            <p>De beste manier om dit probleem op te lossen, is om uw mailserver (MTA)
            stop te zetten en de mailqueue op SPAM-berichten te controleren. 
            Als u verdachte mail vindt, kunt u deze openen om erachter te komen waar deze vandaan komt. 
            Door de MTA stop te zetten, voorkomt u dat de SPAM wordt verstuurd. 
            Nadat het probleem is opgelost, moet u alle SPAM uit de mailqueue halen alvorens u uw MTA opnieuw opstart.</p>

            <h2>Meer informatie</h2>

            <a target'_blank' href='http://blog.returnpath.com/blog/jamie-lawler/trap-tips-avoiding-and-removing-spam-traps'>Tips to avoid SpamTraps</a><br>
            <a target'_blank' href='http://www.activecampaign.com/help/bounces-soft-bounce-vs-hard-bounce/'>Hard/Soft Bounce configuration</a><br>

            ",
    ],

    'SSLV3_VULNERABLE_SERVER' => [
        'name'        => 'SSLv3 kwetsbare server',
        'description' => "
            <h2>Wat is een 'SSLv3 kwetsbare Server'?</h2>

            <p>POODLE (de 'Padding Oracle On Downgraded Legacy Encryption' aanval) is de naam voor een
            OpenSSL bug. Deze bug heeft een impact op vrijwel elk systeem dat op OpenSSL gebaseerde encryptie gebruikt.</p>

            <h2>Waarom is dit een probleem?</h2>

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

            <h2>Aanbevolen actie</h2>

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

            <p>ssl_protocols TLSv1 TLSv1.1 TLSv1.2 TLSv1.3;

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

            <p>SSLv3 kan op uw Apache server uitgeschakelt worden met behulp van de volgende config:

            SSLProtocol All -SSLv2 -SSLv3

            Dit geeft support voor TLSv1.0, TLSv1.1, TLSv1.2 en TLSv1.3, maar haalt expliciet de ondersteuning voor SSLv2 and SSLv3 weg. Check de config en start Apache opnieuw op.

            apachectl configtest

            sudo service apache2 restart</p>


            <p>After patching all the service(s) and confirming the bug is nog longer present on
            your system its highly recommended to get a NEW SSL certificate (including key, csr, etc)
            as it might have been comprised. Most SSL suppliers will issue such a certificate for free</p>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://disablesslv3.com/'>Disable SSLv3 - a community-powered step-by-step tutorial</a><br>

            ",
    ],

    'SPAMVERTISED_WEBSITE' => [
        'name'        => 'Spamvertised website',
        'description' => "
            <h2>Wat is een 'Spamvertised website'?</h2>

            <p>Als een site wordt ge-'spamvertised', betekent dat dat er naar deze site wordt gelinkt in spam e-mails.</p>

            <h2>Waarom staat mijn website in spam e-mails?</h2>

            <p>De reden hiervoor is meestal dat uw site (of DNS) op een één of andere manier gecompromitteerd is.
            Spammers of hackers voegen een redirect aan uw site toe die bezoekers naar diens malafide site linkt.
            Dit omdat hun eigen site waarschijnlijk reeds een slechte reputatie heeft of bekend staat als verdacht.
            
            This is generally the case when your site (or DNS) has been comprimised in some way.
            Spammers or hackers often add a redirect from your site to their Spam site.
            Spammers would prefer to use your website for their links, as most likely their site is already known as bad. </p>

            <h2>Aanbevolen actie</h2>

            <p>Controleer uw website en DNS op tekenen van compromis.</p>

            <h2>Tips om dit op te lossen</h2>

            <ul>
            <li>Indien dit om een CMS (WordPress, Drupal, Joomla, etc.) gaat, check of er updates voor uw add-ons en plugins beschikbaar zijn. Voer deze uit waar mogelijk.</li>
            <li>Indien dit een 'standaard' website is, controleer op tekeken van infectie of onbekende links op uw pagina's. Neem stappen om deze te verwijderen.</li>
            <li>Zodra het probleem is opgelost, laat uw URL van blocklists afhalen.</li>
            </ul>

            <h2>Meer informatie</h2>

            <a target'_blank' href='https://wordpress.org/plugins/sucuri-scanner/'>WordPress Security scanner (Sucuri)</a><br>

            ",
    ],

    'OPEN_ELASTICSEARCH_SERVER' => [
        'name'        => 'Open ElasticSearch Server',
        'description' => "
            <h2>Wat is een 'Open ElasticSearch Server'?</h2>

            <p>Elasticsearch is een op Lucene gebaseerde zoekserver. een gedistibuteerde zoekmachine met een RESTful web interface en schema-free JSON documenten.
            
            Elasticsearch is a search server based on Lucene. It provides a distributed,
            multitenant-capable full-text search engine with a RESTful web interface and
            schema-free JSON documents.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>
            
            Your system has an ElasticSearch instance running (see www.elastic.co for
            more information) which is accessible on the internet. On its own, ElasticSearch
            does not support authentication or restrict access to the datastore, so it is
            possible that any entity that can access the ElasticSearch instance may have
            complete control.</p>

            <p>
            
            This is especially problematic if this instance has dynamic scripting running.
            The scripting engine can be abused to launch a denial of service attack.</p>

            <h2>Aanbevolen actie</h2>

            <p
            
            >Either bind this service only to non-public facing connections or add a firewall
            to block the port ElasticSearch is running on.</p>

            <h2>Tips to resolve this matter</h2>

            <p>Lees de documentatie van Elasticsearch over hoe u uw Elasticsearch-instantie het beste kunt beveiligen.</p>

            <h2>Meer informatie</h2>

            <a href='https://bouk.co/blog/elasticsearch-rce/'>Insecure default in Elasticsearch enables remote code execution (Engels)</a><br>
            ",
    ],

    'COPYRIGHT_INFRINGEMENT' => [
        'name'        => 'Copyrightschending',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'POSSIBLE_DDOS_SENDING_SERVER' => [
        'name'        => 'Mogelijke DDoS Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'DDOS_SENDING_SERVER' => [
        'name'        => 'DDoS Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'OPEN_PORTMAP_SERVER' => [
        'name'        => 'Open Portmapper Server',
        'description' => "
            <h2>Wat is een 'Open Portmapper Server'?</h2>

            <p>De 'port mapper' (rpc.portmap of rpcbind) is een 'remote procedure call' (RPC) dienst 
            op TCP of UDP port 111 dat op servers draait en informatie geeft over
            de de diensten die hierop draaien alsmede hun poortnummers, zals bijvoorbeeld NFS.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Zodra een aanvaller een actieve port 111 op een systeem ontdekt. kan die deze informatie gebruiken
            om meer te weten te komen over welke services hierop draaien. Dit is vaak de eerste stap in een hackaanval.</p>

            <p>Deze functie kwordt tevens ook door hackers misbruikt voor het uitvoeren 
            van een zogenaamde 'Amplification Attack'; een speciaal type DDoS.</p>

            <p>Een amplificatie aanval is alleen uit te voeren in combinatie met zogeheten 'reflection'.
            Dit is dat een aanvaller doet alsof diens IP-Adres dat van het slachtoffer is (spoofing). 
            Als de aanvaller geen reflection toe zou passen, dan zou die zichzelf namelijk aanvallen.</p>

            <p>De aanvaller stuurt een packet dat afkomstig lijkt te zijn van het slachtoffer naar een server die daar direct antwoord op geeft. 
            Omdat het IP-adres gespooft is, wordt de opgevraagde data naar het slachtoffer verzonden.</p>

            <p>Dit heeft twee gevolgen: allereerst, maakt dit de werkelijke bron van de aanval heel moeilijk te traceren. 
            Verder, indien er veel servers voor de aanval worden misbruikt, kan de aanval bestaan uit een overweldigend aantal packets afkomstig vanaf servers over de hele wereld verspreid.</p>

            <p>Reflection aanvallen kunnen nog krachtiger zijn wanneer deze gecombineerd zijn met amplification; als een klein packet een groot antwoord krijgt van de server(s). 
            In dat geval stuurt de aanvaller een klein packet van een gespooft IP-adres waarna de server(s) een groot antwoord terug stuurt. </p>

            <p>Bij amplificatie-aanvallen zoals dat kunnen kwaadwillenden dus met een klein beetje bandbreedte van 
            een paar machines een grote hoeveelheid dataverkeer afkomstig vanaf het hele internet op een slachtoffer richten.</p>

            <h2>Aanbevolen actie</h2>

            <p>Wij adviseren om enkel RPC calls van vertrouwde bronnen toe te laten.
            Dit kan worden gedaan door al het verkeer voor RPC services te laten vallen
            en alleen verbindingnen van bekende IP-adressen toe te staan.</p>
            ",
    ],

    'MALWARE_INFECTION' => [
        'name'        => 'Malware infectie',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'COMMENT_SPAM' => [
        'name'        => 'Commentaar Spam',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'HACK_ATTACK' => [
        'name'        => 'Hackaanval',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'INFORMATIONAL' => [
        'name'        => 'Informationeel',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'LOGIN_ATTACK' => [
        'name'        => 'Loginaanval',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'DICTIONARY_ATTACK' => [
        'name'        => 'Woordenboekaanval',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'RULE_BREAKER' => [
        'name'        => 'Regelbreker',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'HAVE_I_BEEN_PWNED_DOMAIN_FOUND' => [
        'name'        => 'Have I been pwned breach',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    
    'OPEN_AFP_SERVER' => [
        'name'        => 'Open Apple Filing Protocol (AFP) Server',
        'description' => '
            <h2>What is a \'Open AFP Server\'?</h2>

            <p>Apple Filing Protocol (formerly AppleTalk Filing Protocol) is a transport protocol
            that Apple macOS uses to share resources.  For example, if a macOS system wants
            to access a shared directory on another system, it can use AFP. AFPv3 uses TCP
            port 548. The protocol can also support other options including changing user passwords.</p>

            <h2>Why would this be bad?</h2>

            <p>This report identifies hosts that have Apple Filing Protocol (AFP) Service running and accessible to the world
             on the internet. AFP can allow for information gathering on a target host as information about the host is contained in
             the protocol messages.</p>

            <p>In addition your sure will be targetted by brute force attacks.</p>

            <h2>Recommended action</h2>

            <p>Disable public access to AFP.</p>

            <h2>Tips how to resolve this matter</h2>
            <ul>
            <li>Firewall port TCP/548 and use AFP over SSH to gain access to your system/li>
            </ul>

            ',
    ],

    'OPEN_FTP_SERVER' => [
        'name'        => 'Open FTP Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],

    'OPEN_HTTP_SERVER' => [
        'name'        => 'Open HTTP Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar..
            ',
    ],

    'OPEN_RSYNC_SERVER' => [
        'name'        => 'Open rsync Server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar..
            ',
    ],
    'OPEN_PROXY_SERVER' => [
        'name'        => 'Open proxy server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'OPEN_UBIQUITI_SERVER' => [
        'name'        => 'Open Ubiquiti server',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'BRUTE_FORCE_ATTACK' => [
        'name'        => 'Brute Force aanval',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'AMPLICATION_DDOS_VICTIM' => [
        'name'        => 'DDOS amplictifcatie slachtoffer',
        'description' => "        
            <h2>Honeypot Amplification DDoS Events Report</h2>
            
            <p>This report contains information about honeypot observed amplification DDoS events. If you are seeing this report, it 
            means that your IP was DDoSed using other hosts/services as reflectors.</p>
            
            <p>This category of DDoS attacks utilizes UDP-based, open, amplifiable services to reflect packets to a victim, by 
            spoofing the source IP address of the packets sent by the amplifier to the victim’s IP address.</p>
            
            <p>Depending on the protocol and type of open services abused, the size of the original packet content sent by the attacker 
            can be amplified in the service response multiple times (even by a factor of hundreds), flooding the victim with packets 
            and enabling DDoS.</p>
            
            <p>Honeypots that emulate open and amplifiable services can be used to detect this kind of abuse. However, as the source 
            of these attacks is spoofed to the victim address, it is possible only to report on victims being abused, not on the true 
            source of the DDoS.</p>
            
            <p>You can read more about our DDoS attack observations <a href='https://sissden.eu/blog/amp2018'>in the SISSDEN blog 
            entry on observations on DDoS attacks in 2018</a>. For more insight into how amplifiable DDoS attacks work, check out this 
            <a href='https://christian-rossow.de/articles/Amplification_DDoS.php'>writeup and paper by Christian Rossow</a>,&nbsp;as 
            well as the <a href='https://www.us-cert.gov/ncas/alerts/TA14-017A'>US-CERT Alert (TA14-017A)</a>.</p>
            
            <p>This report contains information about the IP that was attacked (set to src_ip) and the port that was abused on the 
            honeypot to try to make it attack your IP (set to dst_port).</p>
    
            ",
    ],
    'ACCESSIBLE_ADB_REPORT' => [
        'name'        => 'Open Android Debug Bridge',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'ACCESSIBLE_APPLE_REMOTE_DESKTOP_ARD_REPORT' => [
        'name'        => 'Open Apple Remote Desktop',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'CAIDA_IP_SPOOFER_REPORT' => [
        'name'        => 'CAIDA IP spoofer',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'DRONE_BOTNET_DRONE_REPORT' => [
        'name'        => 'Botnetdrone',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'NETCORE_NETIS_ROUTER_VULNERABILITY_SCAN_REPORT' => [
        'name'        => 'Kwetsbare Netcore/Netis router',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'OPEN_DB2_DISCOVERY_SERVICE_REPORT' => [
        'name'        => 'Open DB2 discovery service ',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'OPEN_MQTT' => [
        'name'        => 'OPEN MQTT service ',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'OPEN_COAP' => [
        'name'        => 'Open COAP service',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'OPEN_IPP' => [
        'name'        => 'Open IPP service',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'OPEN_RADMIN' => [
        'name'        => 'Open RAdmin service',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'OPEN_RDPEUDP' => [
        'name'        => 'Open Microsoft Remote Desktop Protocol service',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'OPEN_BASIC_AUTH_SERVICE' => [
        'name'        => 'Open Basic Authenticatie service',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'DARKNET' => [
        'name'        => 'Service contacting darknets',
        'description' => 'Er is nog geen informatie over deze classificatie beschikbaar.',
    ],
    'eicc(stix2)' => [
        'name'        => 'ei.cc',
        'description' => '
            ESET threat intelligence heeft ontdekt dat de genoemde host de rol van Command & Control Centre voor een botnet speelt. Er zijn mogelijk meer details beschikbaar op de "technische details" pagina.
            ',
    ],

    'eibotnet(stix2)' => [
        'name'        => 'ei.botnet',
        'description' => '
            ESET threat intelligence heeft ontdekt dat deze host deelneemt aan een botnet. Er zijn mogelijk meer details beschikbaar op de "technische details" pagina.
            ',
    ],

    'eidomainsv2(stix2)' => [
        'name'        => 'ei.domains',
        'description' => '
            ESET threat intelligence heeft ontdekt dat de host gebruikt wordt om malware op het genoemde domein te verspreiden. Er zijn mogelijk meer details beschikbaar op de "technische details" pagina.
            ',
    ],

    'eiurls(stix2)' => [
        'name'        => 'ei.urls',
        'description' => '
            ESET threat intelligence heeft ondekt dat de host gebruikt wordt om malware op de genoemde URL te verspreiden. Er zijn mogelijk meer details beschikbaar op de "technische details" pagina.
            ',
    ],

    'VULNERABLE_SMTP_SERVER' => [
        'name'          => 'Kwetsbare SMTP server',
        'description'   => "
            <h2>Wat is een 'Kwetsbare SMTP server'?</h2>
            
            <p>Het is ontdekt dat de host - een server die mail verstuurd - een kwetsbare SMTP software heeft draaien.</p>

            <h2>Waarom is dit een probleem?</h2>
        
            <p>Hackers zouden misbruik van deze kwetsbaarheid kunnen maken waardoor de DMTP server gehackt zou kunnen worden.</p>

            <h2>Aanbevolen actie:</h2>

            <p>Update de SMPT doftware naar de nieuwste versie.</p>
            ",
    ],

    'OPEN_AMQP' => [
        'name'          => 'Toegankelijke Advanced Message Queuing Protocol (AMQP) server',
        'description'   => '
            <h2>Wat is een AMQP server?</h2>

            <p>AMQP is een open internet protocol voor \'business messaging\'. Het wordt ook ingezet voor het beheren van IoT (Internet of Things) apparaten.</p>

            <h2>Waarom is dit een probleem?</h2>

            <p>Hoewel AMQP wel de optie heeft om encrypted te communiceren via TLS, zijn veel instanties op het internet zo ingesteld dat authentificatie en boodschappen via cleartext gaan.
            Verder zijn er in het verleden vele kwetsbaarheden in AMQP broker sotware-implementaties gevonden die het mogelijk maken om o.a. authentificatie te omzeilen, 
            boodschappen te onderscheppen of zelfs remote code execution of DDoS aanvallen uit te voeren.</p>

            <h2>Aanbevolen actie:</h2>

            <p>Zorg ervoor dat de AMQP server up-to-date en niet vrij toegankelijk vanaf het internet is als dit niet nodig is.</p>
            ',
    ],

    'OPEN_SSH_SERVER' => [
    'name'          => 'Open SSH service',
    'description'   => '
        <p> Dit rapport identificeert hosts die een Secure Shell (SSH) service - die vanaf het internet toegankelijk is - hebben draaien. </p>

        <p> Dit betekent niet per se dat er iets met dit systeem mis is, maar als de SSH op dit syteem (of de versie die runt) er niet op lijkt te horen staan, is het wellicht een goed idee om hier nader naar te kijken. </p>
        ',
    ],

    'OPEN_SMTP_SERVER' => [
        'name'          => 'Open SMTP service',
        'description'   => '
            <p> Dit rapport bevat een lijst met toegankelijke SMTP servers. </p>

            <p> Dit is slechts een population scan – er worden geen kwetsbaarheden gemeld – maar netwerkbeheerder zouden zich bewust moeten zijn van enige onbedoelde bloodstelling van SMTP servers en zouden moeten verifiëren dat alle naar de nieuwste softwareversie gepatcht zijn. </p>
            ',
    ],

    'OPEN_ICS' => [
        'name'          => 'Open Industrial Control Service (ICS) application',
        'description'   => '
            <p> Dit rapport bevat een lijst met apparaten die reageren op een aantal gespecializeerde ICS/OT scans. 
            Ook bevat het de make-and-model informatie en de raw responses die ontvangen zijn. </p>

            <p> Het is onwaarschijnlijk dat dit soort apparaten toegankelijk hoeven te zijn voor queries vanaf het openbare internet, 
            dus tenzij u een honeypot draait, is het sterk aangeraden om - als u een rapportage voor uw netwerk/kring ontvangt - 
            hier onmiddelijk actie op te ondernemen en de toegang tot dit apparaat te beperken/firewallen. </p>
            ',
    ],

    'OPEN_POSTGRESQL_SERVER' => [
        'name'          => 'Open PostgreSQL Server.',
        'description'   => '
            <p> Dit rapport identificeert toegankelijke PostgreSQL server instances op port 5432/TCP. </p>

            <p> Het is onwaarschijnlijk dat uw PostgreSQL server benaderbaar hoeft te zijn voor externe verbindingen vanaf het internet (oftewel: een mogelijk extern aanvalsoppervlak). Indien u wel een rapport ontvangt op uw netwerk/kring, zorg er dan voor dat dataverkeer naar uw PostgreSQL-instantie gefilterd wordt. Pas authentificatie op de server toe. </p>
            ',
    ],

    'OPEN_STUN_SERVICE' => [
        'name'          => 'Open STUN Service',
        'description'   => '
            <p> Dit rapport identificeert toegankelijke STUN (Session Traversal Utilities for NAT) servers op port 3478/udp.
            Zoals omschreven op Wikipedia (EN): "STUN is a standardized set of methods, including a network protocol, for traversal of network address translator (NAT) gateways in applications of 
            real-time voice, video, messaging, and other interactive communications. 
            The STUN service is known to be a potential UDP message amplifier, that can be abused for reflected DDoS attacks." </p>

            <p> Overweeg om STUN standaard over TCP te gebruiken. </p>
',
    ],

    'OPEN_ERLANG_PORTMAPPER_DAEMON' => [
        'name'          => 'Open Erlang Port Mapper Daemon server',
        'description'   => '
            <p> Dit rapport identificeert toegankelijke Erlang Port Mapper Daemon (EPMD) servers op port 4369/tcp.
            Deze daemon doet dienst als name server for hosts die bij gedistributeerde Erlang berekeningen betrokken zijn. </p>

            <p> Het is onwaarschijnlijk dat uw EPMD server externe verbindingen vanaf het internet toe hoeft te staan (gezien dit een mogelijk extern aanvalsvalk kan zijn).
            Zorg ervoor dat het verkeer naar deze dienst gefirewalled wordt. Als u dit dit rapport ontvangt voor uw netwerk of kring. zorg ervoor dat verkeer richting deze service gefirewalled wordt.</p>
',
    ],

    'OPEN_SOCKS_PROXY' => [
        'name'          => 'Open SOCKS Proxy service',
        'description'   => '
            <p>Dit rapport identificeert hosts die een vanaf het internet toegankelijke SOCKS proxy versie 4 of 5 service hebben draaien op port 1080/TCP.
            Het SOCKS protocol maakt het mogelijk voor een client en server om via een proxy server packets uit te wisselen. Deze proxy servers kunnen optioneel authentificatie ondersteunen.</p>

            <p> Open proxy servers die het proxyen van diensten zonder authentificatie toestaan, worden vaak misbruikt. Andere - zelfs met authentificatie - zouden ook gevolgen voor de veiligheid kunnen hebben.</p>

            <p> Zoals bij alle remote access tools, is het belangrijk om ervoor te zorgen dat ook een SOCK proxy service secuur geconfigureerd is.
            Ook is het belangrijk om de mogelijke beveilingsimplicaties van het overal toegankelijk maken via het internet van deze service mee te nemen. </p>
            ',
    ],

    'DEVICE_IDENTIFICATION' => [
        'name'          => 'Device identification',
        'description'   => '
            <p>Dit is een \'device population report\' - Er wordt geen oordeel gevelt over eventuele kwetsbaarheden aanwezig op het apparaat.
            Deze rapportage is bedoeld om ontvangers een beter overzicht te geven van wat voor soorten apparaten er in diens netwerk actief zijn.
            De inhoud van deze rapportage is tevens enkel gebaseerd op wat er publiek toegankelijk vanaf het internet is. </p>
            ',
    ],

    'ACCESSIBLE_XDMCP_SERVICE_REPORT' => [
        'name'          => 'Open XDCMP Service',
        'description'   => '
            <p> Dit rapport identificeert hosts die een X Display Manager service draaiende hebben die op het Internet benaderbaar is. </p>

            <p> XDMCP lekt informatie over het hostsysteem. Ook kan deze service in een amplificatie-aanval gebruikt worden waardoor ongeveer 7x amplificatie wordt gecreëerd.
            Het maakt niet uit of XDCMP met "Willing" of "Unwilling" antwoord, de service geeft altijd hetzelfde niveau amplificatie. </p>
            ',
    ],

    'VULNERABLE_EXCHANGE_SERVER' => [
        'name'          => 'Kwetsbare Microsoft Exchange server',
        'description'   => '
            <h2>Wat is een "Kwetsbare Mirosoft Exchange server"></h2>
            
            <p>De host draait een kwetsbare versie van Microsoft Exchange.</p>

            <h2>Waarom is dit een probleem?</h2>
        
            <p>Kwaadwillenden zouden misbruik kunnen maken van de kwetsbaarheid in de Microsoft Exchange server, waardoor deze gehackt zou kunnen worden.</p>

            <h2>Aanbevolen actie</h2>

            <p>Update Microsoft Exchange naar de nieuwste versie.</p>
            ',
    ],

    'ACCESSIBLE_MSMQ_SERVICE' => [
        'name'         => 'Toegankelijke Microsoft Message Queuing service',
        'description'  => '
            <p> Dit rapport identificeert toegankelijke Microsoft Message Queuing (MSMQ) servers op port 1801/TCP. Deze service kan optioneel geactiveerd worden op Windows besturingssystemen, waaronder Windows Server 2022 en Windows 11. </p>',
    ],

    'ACCESSIBLE_SLP_SERVICE' => [
        'name'         => 'Toegankelijke Service Location Protocol (SLP) Service',
        'description'  => '
            <p> Dit rapport identificeert toegankelijke SLP (Service Location Protocol) services op port 427/TCP en 427/UDP. Service Location Protocol (SLP, srvloc) is een service ontdekkings-protocol dat computers en andere apparaten in staat stelt services in een local area network (LAN) te vinden zonder dat daar verder configuratie aan vooraf gaat. </p>',
    ],

    'ACCESSIBLE_BGP_SERVICE' => [
        'name'         => 'Toegankelijke Border Gateway Protocol (BGP) Service',
        'description'  => '
            <p> Dit rapport identificeert toegankelijke Border Gateway Protocol (BGP) servers op port 179/TCP. </p>',
    ],

];
