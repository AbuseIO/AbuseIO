<?php
function parse_cleanmx_de($message) {

    $source = "CleanMX.DE";

    // Attempt to find ARF report
    if (!empty($message['store']) && !empty($message['attachments'])) {
        // Report should be named 'report.txt'
        foreach ($message['attachments'] as $k => $f) {
            if ($f == 'report.txt') {
                $arf = $f;
                break;
            }
        }
    }

    // Parse ARF report, if we have one
    if (!empty($arf)) {

        // Fetched from schema at http://support.clean-mx.de/schema/xarf.json
        $typeMap = array(
            'login-attack'=>'Login attack',
            'info'=>'Informational',
            'harvesting'=>'Harvesting',
            'hack-attack'=>'Hack attack',
            'comment spam'=>'Comment Spam',
            'Denial of service'=>'DDoS attack',
        );

        // Read and parse report
        $report = file_get_contents($message['store'].'/'.$k.'/'.$arf);
        preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/',$report,$regs);
        $fields = array_combine($regs[1],$regs[2]);

        if (empty($fields['Report-Type']) || !array_key_exists($fields['Report-Type'],$typeMap)) {
            logger(LOG_ERR, __FUNCTION__ . " Unable to detect report type in message from ${source} subject ${message['subject']}");
            return false;
        }

        if($fields['Report-Type'] == 'info') {
            $type = 'INFO';
        } else {
            $type = 'ABUSE';
        }

        $outReport = array(
                            'source'        => $source,
                            'ip'            => $fields['Source'],
                            'class'         => $typeMap[$fields['Report-Type']],
                            'type'          => $type,
                            'timestamp'     => strtotime($fields['Date']),
                            'information'   => $fields
                          );

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

        logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
        return true;

    // Some reports don't contain an ARF report, but a table with abuse info
    } else if (preg_match_all('/\n\|([^\|]*)[ ]*\|([^\|]*)[ ]*\|([^\|]*)[ ]*\|([^\|]*)[ ]*\|([^\|]*)[ ]*\|([^\| \r\n]*)/',$message['body'],$regs)) {
        
        // Class depends on mail subject
        $subjectMap = array(
            '/clean-mx-portals/'=>false, // Determine class based on report content
            '/clean-mx-phishing/'=>'Phishing website',
        );

        foreach ($subjectMap as $regex => $class) {
            if (preg_match($regex, $message['subject'])) {
                break;
            }
        }

        // Convert regex results into a report array
        array_shift($regs);
        $reports = array();
        foreach ($regs as $r) {
            $name = trim(array_shift($r));
            foreach ($r as $i => $value) {
                $reports[$i][$name] = trim($value);
            }
        }

        // For portal reports
        $portalMap = array(
            'cleanmx_phish' => 'Phishing website',
            'cleanmx_spamvertized' => 'Spamvertised web site'
        );

        // Save reports
        foreach ($reports as $report) {

            if (!empty($report['virusname'])) {
                if (array_key_exists($report['virusname'],$portalMap)) {
                    $class = $portalMap[$report['virusname']];
                } else {
                    // Don't process unknown classes, log and continue with next report
                    logger(LOG_ERR, __FUNCTION__ . " Unable to determine portal class for {$report['virusname']}");
                    continue;
                }
            }

            // Sanity checks (skip if required fields are unset)
            if (empty($report['ip']) || empty($class) || empty($report['date'])) continue;

            $outReport = array(
                            'source'        => $source,
                            'ip'            => $report['ip'],
                            'class'         => $class,
                            'type'          => 'ABUSE',
                            'domain'        => @$report['domain'],
                            'uri'           => @$report['Url'],
                            'timestamp'     => strtotime($report['date']),
                            'information'   => $report
                          );

            $reportID = reportAdd($outReport);
            if (!$reportID) return false;
            if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }
        }

        logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
        return true;

    } else {
        logger(LOG_ERR, __FUNCTION__ . " Unable to parse message from ${source} subject ${message['subject']}");
    }
}
?>
