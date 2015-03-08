<?php
function collect_snds($config) {
    // Fetch Microsoft SNDS report for IP status
    if (COLLECTOR_SNDS_KEY == "") {
        logger(LOG_DEBUG,'No SNDS key specified in config, skipping SNDS reporting');
        return false;

    } else if ($data = file_get_contents('https://postmaster.live.com/snds/ipStatus.aspx?key='.COLLECTOR_SNDS_KEY)) {
        $sndsMap = array(
            'E-mail address harvesting'=>array(
                'class'=>'Harvesting',
                'type'=>'ABUSE',
                'information'=>array(),
            ),
            'Symantec Brightmail'=>array(
                'class'=>'RBL Listed',
                'type'=>'INFO',
                'information'=>array(
                    'delisting_url'=>'http://ipremoval.sms.symantec.com/'
                ),
            ),
            'SpamHaus SBL/XBL'=>array(
                'class'=>'RBL Listed',
                'type'=>'INFO',
                'information'=>array(
                    'delisting_url'=>'https://www.spamhaus.org/lookup/',
                ),
            )
        );
        preg_match_all('/([^,]+),([^,]+),([^,]+),([^\r\n]+)\r?\n/',$data, $regs);
        $first_ip = $regs[1];
        $last_ip = $regs[2];
        $blocked = $regs[3];
        $source = $regs[4];
        foreach ($blocked as $k => $status) {
            if ($status == 'Yes') {
                $first = ip2long($first_ip[$k]);
                $last = ip2long($last_ip[$k]);
                if (!empty($first) && !empty($last) && $first <= $last) {
                    for ($x = $first; $x <= $last; $x++) {
                        if (array_key_exists($source[$k],$sndsMap)) {
                            if (!reportAdd(array(
                                'source'=>'Microsoft SNDS',
                                'ip'=>long2ip($x),
                                'class'=>$sndsMap[$source[$k]]['class'],
                                'type'=>$sndsMap[$source[$k]]['type'],
                                'timestamp'=>time(),
                                'information'=>array_merge($sndsMap[$source[$k]]['information'],array(
                                    'details'=>$source[$k]
                                ))
                            ))) break;
                        } else {
                            logger(LOG_ERR,'Unknown class when importing SNDS report: '.$source[$k]);
                            continue;
                        }
                    }
                } else {
                    logger(LOG_ERR,'Unable to calculate IP range when importing SNDS report: '.$first_ip[$k].' - '.$last_ip[$k]);
                    continue;
                }
            } else {
                logger(LOG_ERR,'Unknown status when importing SNDS report: '.$status);
                continue;
            }
        }
    } else {
        logger(LOG_ERR,'Unable to import SNDS report');
        return false;
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message ");
    return true;
}
?>
