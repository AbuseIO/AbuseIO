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
** Function: parse_juno
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_juno($message) {

    $source = 'Juno.com';
    $type   = 'ABUSE';

    // Read and parse report
    preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/',$message['arf']['report'],$regs);
    $fields = array_combine($regs[1],$regs[2]);

    if (empty($fields['Feedback-Type']) || $fields['Feedback-Type'] != 'abuse') {
        logger(LOG_ERR, __FUNCTION__ . " Unable to detect report type in message from ${source} subject ${message['subject']}");
        return false;
    }

    $outReport = array(
                        'source'        => $source,
                        'ip'            => $fields['Source-IP'],
                        'class'         => 'SPAM',
                        'type'          => 'ABUSE',
                        'timestamp'     => strtotime($fields['Arrival-Date']),
                        'information'   => $fields
                      );

    $reportID = reportAdd($outReport);
    if (!$reportID) return false;
    if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;
}
?>
