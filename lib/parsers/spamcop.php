<?php
function parse_spamcop($message) {
    $outReport                  = array('source' => 'Spamcop');
    $outReport['information']   = array();
    $outReport['type']          = 'ABUSE';

    if (
        strpos($message['from'], "@reports.spamcop.net>") !== FALSE && 
        isset($message['arf']) && 
        is_array($message['arf']) && isset($message['arf']['report'])
    ) {
        // We've got a ARF formatted message
        // The e-mail parses should have noticed the multipart elements and created a 'arf' array

        if (isset($message['arf']['headers']['from'])) {
            $outReport['information']['from'] = $message['arf']['headers']['from'];
        }
        if (isset($message['arf']['headers']['return-path'])) {
            if (!is_array($message['arf']['headers']['return-path'])) {
                $outReport['information']['return-path'] =  $message['arf']['headers']['return-path'];
            } else {
                $outReport['information']['return-path'] = $message['arf']['headers']['return-path'][0];
            }
        }
        if (isset($message['arf']['headers']['subject'])) {
            $outReport['information']['subject'] = $message['arf']['headers']['subject'];
        }
        if (isset($message['arf']['headers']['x-mailer'])) {
            $outReport['information']['x-mailer'] = $message['arf']['headers']['x-mailer'];
        }
        if (is_array($message['arf']['headers']['received'])) {
            foreach( $message['arf']['headers']['received'] as $id => $received) {
                $field = "header_line". ($id + 1);
                $outReport['information'][$field] = "received " . $received;
            }
        }

        //TODO uitzoeken wat verschillende feedback-type's zijn
        //TODO als ^^ niet de class word, dan class herkenning (spam, bounce, spamvertised)
        $match = "^Feedback-Type: (?<type>.*)\r\n?\r?\nUser-Agent: (?<agent>.*\r\n?\r?\n\s.*)\r\n?\r?\nVersion: (?<version>.*)\r\n?\r?\nReceived-Date: (?<date>.*)\r\n?\r?\nSource-IP: (?<ip>.*)\r\n?\r?\n";
        preg_match("/${match}/m", $message['arf']['report'], $match);

        $outReport['class']         = "SPAM"; // ?????
        $outReport['ip']            = $match['ip'];
        $outReport['timestamp']     = strtotime($match['date']);

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

    } elseif (strpos($message['from'], "@reports.spamcop.net") !== FALSE) {
        //TODO uitpakken report voor IP en datum
        // We've got a SPAMCOP formatted message
        // most inline SPAM messages contain the original SPAM message
        // Lets attempt to grab that part of the message and collect the interesting bits
        $body            = explode("[ Offending message ]", $message['body']);
        if (isset($body[1])) {
            $spam_message   = receive_mail(array('type' => 'INTERNAL', 'message' => $body[1]));

            if (isset($spam_message['headers']['from'])) {
                $outReport['information']['from'] = $spam_message['headers']['from'];
            }
            if (isset($spam_message['headers']['return-path'])) {
                $outReport['information']['return-path'] = $spam_message['headers']['return-path'];
            }
            if (isset($spam_message['headers']['subject'])) {
                $outReport['information']['subject'] = $spam_message['headers']['subject'];
            }
            if (isset($spam_message['headers']['x-mailer'])) {
                $outReport['information']['x-mailer'] = $spam_message['headers']['x-mailer'];
            }
            if (is_array($spam_message['headers']['received'])) {
                foreach( $spam_message['headers']['received'] as $id => $received) {
                    $field = "header_line". ($id + 1);
                    $outReport['information'][$field] = "received " . $received;
                }
            }
        }

        // Split the main body text to be able to detect the message type and should end up with
        $tmp = preg_split("/(\r)?\n/", $body[0]);
        // element 1: 'spamcop vXXXX' or 'spamcop summary' types
        // element 2: empty line
        // element 3: domain or IP and the classification
        // element 4: the url to contact spamcop for this report
        // element 5: IP (again) and timestamp 

        if (strpos($tmp[3], "Spamvertised web site") !== false) {
             // This part handles 'Spamvertised web site' complaints
            $tmpvar = explode(": ", $tmp[3]);
            $outReport['class'] = $tmpvar[0];

            $url_info = parse_url($tmpvar[1]);

            if(valid_ip($url_info['host']) == true) {
                $url_info['host'] = "";
            }

            if (!isset($url_info['port']) && $url_info['scheme'] == "http") {
                $url_info['port'] = "80";
            } elseif (!isset($url_info['port']) && $url_info['scheme'] == "https") {
                $url_info['port'] = "443";
            } elseif(!isset($url_info['port'])) {
                $url_info['port'] = "";
            }

            if (!isset($url_info['path'])) {
                $url_info['path'] = "/";
            }

            $outReport['domain']        = $url_info['host'];
            $outReport['uri']           = $url_info['path'];
            $outReport['url']           = $tmpvar[1];

            $outReport['information']['scheme'] = $url_info['scheme'];
            $outReport['information']['port']   = $url_info['port'];

            $tmpvar = preg_split("/((; )|( is ))/i", $tmp[5]);
            $outReport['ip']            = $tmpvar[1]; 
            $outReport['timestamp']     = strtotime($tmpvar[2]);

        } elseif (strpos($tmp[3], "Email from") !== false) {
            // This part handles 'spam' complaints
            $outReport['class']         = "SPAM";

            $tmpvar = preg_split("/(( from )|( \/ ))/i", $tmp[3]);
            $outReport['ip']            = $tmpvar[1];
            $outReport['timestamp']     = strtotime($tmpvar[2]);
            $outReport['information']['reply_url'] = $tmp[4];

        } elseif (strpos($tmp[3], "Unsolicited bounce from") !== false) {
            // This part handles 'bounce' complaints
            // TODO
        } else {
            logger(LOG_ERR, __FUNCTION__ . " Unable to match the report, perhaps a new classification type?");
            logger(LOG_WARNING, __FUNCTION__ . " FAILED message from ${outReport['source']} subject ${message['subject']}");
            return false;
        }

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

    } elseif ($message['subject'] == "[SpamCop] summary report") {
        // Only trap, mole and simp are interesting. Ignore the user field
        // Only table the summary table from the body and turn it into a named array
        $start = strpos($message['body'], "     IP_Address Start/Length Trap User Mole Simp Comments\n                RDNS") + 80;
        $stop  = strpos($message['body'], "\n\n\n-- Key to Columns --");

        $summaries = substr($message['body'], $start, ($stop - $start));
        $match = "^\s*(?<ip>[a-f0-9:\.]+)\s+(?<date>\w+\s+\d+\s\d+)h\/(?<days>\d+)\s+(?<trap>\d+)\s+(?<user>\d+)\s+(?<mole>\d+)\s+(?<simp>\d+)(?:\s(?<comment>.*))\r?\n\s+(?<rdns>.*)";
        preg_match_all("/${match}/m", $summaries, $matches, PREG_SET_ORDER );

        foreach($matches as $id => $match) {
            if ($match['trap'] > 0 || $match['mole'] > 0 || $match['simp'] > 0) {

                $outReport['information']['trap']       = $match['trap'];
                $outReport['information']['mole']       = $match['mole'];
                $outReport['information']['simp']       = $match['simp'];
                $outReport['information']['comment']    = $match['comment'];

                $outReport['class']         = "SPAM Trap";
                $outReport['ip']            = $match['ip'];
                $outReport['timestamp']     = strtotime($match['date'] . ":00");

                $reportID = reportAdd($outReport);
                if (!$reportID) return false;
                if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

            } else { 
                /* Ignore user mails as we get a more details report from spamcop in a seperate mail */ 
                logger(LOG_DEBUG, __FUNCTION__ . " message item from ${outReport['source']} ignored because its a user message about ${match['ip']} which we already got");
            }
        }
        return true;

    } else {
        logger(LOG_ERR, __FUNCTION__ . " The data from this e-mail was not in a known format");
        return false;
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${outReport['source']} subject ${message['subject']}");
    return true;
}
?>
