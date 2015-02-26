<?php
function parse_ip_echelon($message) {

    $source = 'IP-Echelon';
    $type = 'ABUSE';

    if (
        !empty($message['store']) && !empty($message['attachments']) &&
        $xml = simplexml_load_string(file_get_contents($message['store'].'/'.key($message['attachments']).'/'.array_shift($message['attachments'])))

    ) {
        $information = array(
                                'type'          => (string)$xml->Source->Type,
                                'port'          => (string)$xml->Source->Port,
                                'number_files'  => (string)$xml->Source->Number_Files,
                                // IP Echelon sometimes passes invalid UTF-8, so let's fix that
                                'complainant'   => iconv('UTF-8', 'UTF-8//IGNORE', (string)$xml->Complainant->Entity),
                            );

        $outReport   = array(
                                'source'        => $source,
                                'ip'            => (string)$xml->Source->IP_Address,
                                'class'         => 'Copyright Infringement',
                                'type'          => $type,
                                'timestamp'     => strtotime($xml->Source->TimeStamp),
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
