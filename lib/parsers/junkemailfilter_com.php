<?php
function parse_junkemailfilter_com($message) {

    $source = 'JunkEmailFilter.com';

    // Read and parse report
    preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/',$message['arf']['report'],$regs);
    $fields = array_combine($regs[1],$regs[2]);

    if (empty($fields['Feedback-Type']) || $fields['Feedback-Type'] != 'abuse') {
        logger(LOG_ERR, __FUNCTION__ . " Unable to detect report type in message from ${source} subject ${message['subject']}");
        return false;
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");

    $outReport = array(
                        'source'=>$source,
                        'ip'=>$fields['Source-IP'],
                        'domain'=>false,
                        'uri'=>false,
                        'class'=>'SPAM',
                        'timestamp'=>strtotime($fields['Received-Date']),
                        'information'=>$fields
                      );

    $reportID = reportAdd($outReport);
    if (!$reportID) return false;
    if(KEEP_EVIDENCE == true && $reportID !== true) { evidence_link($message['evidenceid'], $reportID); }

    logger(LOG_ERR, __FUNCTION__ . ' No ARF report found in message');
    return false;
}
?>
