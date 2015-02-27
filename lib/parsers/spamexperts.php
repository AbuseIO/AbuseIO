<?php
function parse_antispamcloud($message) {
    $outReport                  = array('source' => 'SpamExperts');
    $outReport['information']   = array();
    $outReport['type']          = 'ABUSE';

    if (
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

        $match = "([\w\-]+): (.*)[ ]*\r?\n";
        preg_match_all("/${match}/m", $message['arf']['report'], $regs);

        // Fix for duplicate fields
        $new['Original-Rcpt-To'] = "";
        foreach($regs[0] as $index => $reg) {
            if ($regs[1][$index] == "Original-Rcpt-To") {
                $new['Original-Rcpt-To'] .= $regs[2][$index];
                unset($regs[1][$index]);
                unset($regs[2][$index]);
            }
        }
        $regs[1][] = 'Original-Rcpt-To';
        $regs[2][] = $new['Original-Rcpt-To'];

        $fields = array_combine($regs[1],$regs[2]);
        $outReport['information'] = array_merge($outReport['information'], $fields);


        $outReport['class']         = "SPAM";
        $outReport['ip']            = $fields['Source-IP'];
        $outReport['timestamp']     = strtotime($fields['Arrival-Date']);

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${outReport['source']} subject ${message['subject']}");
    return true;
}
?>
