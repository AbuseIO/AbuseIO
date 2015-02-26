<?php
function parse_junkemailfilter_com($message) {

    $source = 'JunkEmailFilter.com';
    $type   = 'ABUSE';

    // Read and parse report
    preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/',$message['arf']['report'],$regs);
    $fields = array_combine($regs[1],$regs[2]);

    if (empty($fields['Feedback-Type']) || $fields['Feedback-Type'] != 'abuse') {
        logger(LOG_ERR, __FUNCTION__ . " Unable to detect report type in message from ${source} subject ${message['subject']}");
        return false;
    }

    $outReport = array(
                        'source'        => $source,
                        'ip'            => $fields['Source-IP'],
                        'class'         => 'SPAM',
                        'type'          => 'ABUSE',
                        'timestamp'     => strtotime($fields['Received-Date']),
                        'information'   => $fields
                      );

    $reportID = reportAdd($outReport);
    if (!$reportID) return false;
    if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;
}
?>
