<?php
function collect_rblscan($config) {
    // RBL's to check
    $rbls = array(
        array(
            'name'=>'Spamhaus',
            'host'=>'zen.spamhaus.org',
            'information' => array(
                'delisting_url'=>'https://www.spamhaus.org/lookup/',
            )
        ),
        array(
            'name'=>'Spamcop',
            'host'=>'bl.spamcop.net',
            'information' => array(
                'delisting_url'=>'https://www.spamcop.net/bl.shtml',
            )
        ),
    );

    //Todo extend array so that return codes are specified per list!

    // Check RBL's for listings for IP's with confirmed abuse reports
    if ($ips = reportIps(strtotime(RBL_SCANNER_DURATION . "ago"))) {
        foreach ($ips as $ip) {
            foreach ($rbls as $rbl) {
                $ip_reversed = implode('.',array_reverse(preg_split('/\./',$ip)));
                $lookup = $ip_reversed.'.'.$rbl['host'];
                if ($result = gethostbyname($lookup)) {
                    $class = '';
                    $rbl['information']['List'] = $rbl['host'];
                    switch ($result) {
                        // No listing
                        case $lookup:
                            break;

                        // Generic spam sources
                        case '127.0.0.2':
                        case '127.0.0.3':
                            $class = 'RBL Listed';
                            $rbl['information']['Type'] = 'Blacklisted';
                            break;

                        // Used by Spamhaus CBL (open proxy / trojans / exploits)
                        case '127.0.0.4':
                        case '127.0.0.5':
                        case '127.0.0.6':
                        case '127.0.0.7':
                            $class = 'RBL Listed';
                            $rbl['information']['Type'] = 'Open Proxy, Trojans or Exploits';
                            break;

                        // Used by Spamhaus PBL (policy listed by network operator) (ignore)
                        case '127.0.0.10':
                        case '127.0.0.11':
                            break;

                        default:
                            logger(LOG_ERR,"Unhandled DNS result received by {$rbl['name']} for IP $ip: $result");
                    }
                    if (!empty($class)) {
                        // Abort if we cannot save a report
                        $outReport = array(
                            'source'=>$rbl['name'],
                            'ip'=>$ip,
                            'class'=>$class,
                            'type'=>'INFO',
                            'timestamp'=>time(),
                            'information'=>$rbl['information']
                        );

                        if (!reportAdd($outReport)) break 2;
                    }
                }
            }
        }
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message ");    
    return true;
}
?>
