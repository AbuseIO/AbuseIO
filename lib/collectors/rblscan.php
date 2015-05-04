<?php
/******************************************************************************
* AbuseIO 3.0
* Copyright (C) 2015 AbuseIO Development Team (http://abuse.io)
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software Foundation
* Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
*******************************************************************************
*
* Feed Collector script that fetches data from RBL's and processes the
* events into reports when needed.
*
******************************************************************************/


/*
** Function: collect_rblscan
** Parameters:
**  config (array): a set of configuration options
** Returns: True when the collection was successfully completed (or false when it doenst)
*/
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
                $lookup = $ip_reversed.'.'.$rbl['host'].'.';
                if ($result = gethostbyname($lookup)) {
                    $class = '';
                    $rbl['information']['listed_in'] = $rbl['host'];
                    switch ($result) {
                        // No listing
                        case $lookup:
                            break;

                        // Generic spam sources
                        case '127.0.0.2':
                        case '127.0.0.3':
                            $class = 'RBL Listed';
                            $rbl['information']['reason'] = 'Spam';
                            break;

                        // Used by Spamhaus CBL (open proxy / trojans / exploits)
                        case '127.0.0.4':
                        case '127.0.0.5':
                        case '127.0.0.6':
                        case '127.0.0.7':
                            $class = 'RBL Listed';
                            $rbl['information']['reason'] = 'Open Proxy, Trojans or Exploits';
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
