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
******************************************************************************/


/*
** Function: parse_project_honeypot
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_project_honeypot($message) {

    $source = "Project Honeypot";
    $type = 'ABUSE';

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
                            'class'=>$typeMap[$regs[2][$k]],
                            'type'=>$type,
                            'timestamp'=>strtotime($regs[3][$k]),
                            'information'=>array()
                          );

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;

}
?>
