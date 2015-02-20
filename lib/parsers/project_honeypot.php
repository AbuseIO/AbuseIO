<?php
function parse_project_honeypot($message) {

    $source = "Project Honeypot";

    $typeMap = array(
        'H'=>'Harvesting',
        'S'=>'SPAM',
        'D'=>'Dictionary attack',
        'C'=>'Comment spam',
        'R'=>'Rule breaker'
    );

    preg_match_all('/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}) \(([HSDCR])\)\r?\n  \- ([\w, :]+)\r?\n/',$message['body'],$regs);

    foreach ($regs[1] as $k => $ip) {
        $outReport = array(
                            'source'=>$source,
                            'ip'=>$ip,
                            'domain'=>false,
                            'uri'=>false,
                            'class'=>$typeMap[$regs[2][$k]],
                            'timestamp'=>strtotime($regs[3][$k]),
                            'information'=>array()
                          );

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidence_link($message['evidenceid'], $reportID); }

    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;

}
?>
