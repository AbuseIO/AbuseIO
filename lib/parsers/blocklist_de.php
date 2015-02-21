<?php
function parse_blocklist_de($message) {

    $source = "Blocklist.DE";

    // Fetched from schema at https://www.blocklist.de/downloads/schema/info_0.1.1.json
    $typeMap = array(
        'login-attack'=>'Login attack',
        'info'=>'Informational',
        'harvesting'=>'Harvesting',
        'hack-attack'=>'Hack attack',
        'regbot'=>'Register bot',
        'ircbot'=>'IRC bot',
        'badbot'=>'Bad bot',
        'ddos'=>'DDoS attack',
        'apacheddos'=>'DDoS attack (apache)',
        'reg-bot'=>'Register bot',
        'bad-bot'=>'Bad bot',
        'irc-bot'=>'IRC bot',
    );

    if (!empty($message['store']) && !empty($message['attachments'])) {

        // Report should be named 'report.txt'
        foreach ($message['attachments'] as $k => $file) {
               if ($file == 'report.txt') break;
        }

        if (empty($file)) {
            logger(LOG_ERR, __FUNCTION__ . " Unable to find abuse report in message from ${source} subject ${message['subject']}");
            return false;
        }

        // Read and parse report
        $report = file_get_contents($message['store'].'/'.$k.'/'.$file);
        preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/',$report,$regs);
        $fields = array_combine($regs[1],$regs[2]);

        if (!array_key_exists($fields['Report-Type'],$typeMap)) {
            logger(LOG_ERR, __FUNCTION__ . " Unable to detect report type in message from ${source} subject ${message['subject']}");
            return false;
        }

        logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");

        $outReport = array(
                            'source'=>$source,
                            'ip'=>$fields['Source'],
                            'domain'=>false,
                            'uri'=>false,
                            'class'=>$typeMap[$fields['Report-Type']],
                            'timestamp'=>strtotime($fields['Date']),
                            'information'=>$fields
                          );

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }
        return true;

    } else {
        logger(LOG_ERR, __FUNCTION__ . " Unable to parse message from ${source} subject ${message['subject']}");
    }
}
?>
