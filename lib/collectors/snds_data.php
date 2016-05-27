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
** Function: collect_snds_data
** Parameters:
**  config (array): a set of configuration options
** Returns: True when the collection was successfully completed (or false when it doenst)
*/

function collect_snds_data($config=array()) {

    // Collect data from 2 days ago (otherwise data might not yet be available)
    $time_ago = '-2 days';
    $collect_date = date('mdy',strtotime($time_ago));

    // Fetch Microsoft SNDS report for IP data
    if (COLLECTOR_SNDS_KEY == "") {
        logger(LOG_DEBUG,'No SNDS key specified in config, skipping SNDS reporting');
        return false;

    } else if ($data = @file_get_contents('https://postmaster.live.com/snds/data.aspx?key='.COLLECTOR_SNDS_KEY.'&date='.$collect_date)) {
        preg_match_all('/([^,]+),([^,]+),([^,]+),([^,]+),([^,]+),([^,]+),([^,]+),([^,]+),([^\r\n]+)\r?\n/',$data, $regs);
        $ips = $regs[1];
        $rcpt = $regs[4];
        $data = $regs[5];
        $recipients = $regs[6];
        $result = $regs[7];
        $complaints = $regs[8];
        foreach ($ips as $k => $ip) {
            if ($result[$k] == 'RED' && $complaints[$k] != '< 0.1%') {
                reportAdd(array(
                    'source'=>'Microsoft SNDS',
                    'ip'=>$ip,
                    'class'=>'SPAM',
                    'type'=>'INFO',
                    'timestamp'=>strtotime($time_ago),
                    'information'=>array(
                        'recipients'=>$recipients[$k],
                        'complaint rate'=>$complaints[$k],
                        'rcpt_commands'=>$rcpt[$k],
                        'data_commands'=>$data[$k],
                    )
                ));
            }
        }
    } else {
        logger(LOG_ERR,'Unable to import SNDS data report');
        return false;
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message ");
    return true;
}

?>
