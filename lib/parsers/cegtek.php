<?php
/*
** Function: parse_cegtek
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_cegtek($message) {

    $source = 'Cegtek';
    $type = 'ABUSE';

    // XML is placed in the body
    if (preg_match('/(?<=- ----Start ACNS XML\n)(.*)(?=\n- ----End ACNS XML)/s',$message['body'],$regs)) {
        $xml = $regs[0];
    }

    if (!empty($xml) && $xml = simplexml_load_string($xml)) {

        // Work around the crappy timestamp used by IP-echelon, i.e.: 2015-05-06T05-00-00UTC 
        // We loose some timezone information, but hey it's close enough ;)
        if (preg_match('/^([0-9-]+)T([0-9]{2})-([0-9]{2})-([0-9]{2})/',$xml->Source->TimeStamp,$regs)) {
            $timestamp = strtotime($regs[1].' '.$regs[2].':'.$regs[3].':'.$regs[4]);
        // Fall back to now if we can't parse the timestamp
        } else {
            $timestamp = time();
        }

        $information = array(
                                'type'          => (string)$xml->Source->Type,
                                'port'          => (string)$xml->Source->Port,
                                'number_files'  => (string)$xml->Source->Number_Files,
                                'complainant'   => (string)$xml->Complainant->Entity,
                            );
        $outReport   = array(
                                'source'        => $source,
                                'ip'            => (string)$xml->Source->IP_Address,
                                'class'         => 'Copyright Infringement',
                                'type'          => $type,
                                'timestamp'     => $timestamp,
                                'information'   => $information,
        );
        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

        logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
        return $reportID;

    } else {
        logger(LOG_ERR, __FUNCTION__." Unable to parse XML ${source} subject ${message['subject']}");
        return false;
    }
}
?>
