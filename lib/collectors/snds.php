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
* Feed Collector script that fetches data from Microsoft SNDS and processes the
* events into reports when needed.
*
******************************************************************************/


/*
** Function: collect_snds
** Parameters:
**  config (array): a set of configuration options
** Returns: True when the collection was successfully completed (or false when it doenst)
*/
function collect_snds($config) {
    // Fetch Microsoft SNDS report for IP status
    if (COLLECTOR_SNDS_KEY == "") {
        logger(LOG_DEBUG,'No SNDS key specified in config, skipping SNDS reporting');
        return false;

    } else if ($data = file_get_contents('https://postmaster.live.com/snds/ipStatus.aspx?key='.COLLECTOR_SNDS_KEY)) {
        $sndsMap = array(
            'E-mail address harvesting'=>array(
                'class'=>'Harvesting',
                'information'=>array(),
            ),
            'Symantec Brightmail'=>array(
                'class'=>'RBL Listed',
                'information'=>array(
                    'delisting_url'=>'http://ipremoval.sms.symantec.com/'
                ),
            ),
            'SpamHaus SBL/XBL'=>array(
                'class'=>'RBL Listed',
                'information'=>array(
                    'delisting_url'=>'https://www.spamhaus.org/lookup/',
                ),
            ),
            'Blocked due to user complaints or other evidence of spamming'=>array(
                'class'=>'SPAM',
                'information'=>array(),
            ),
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
                                'type'=>'INFO',
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
