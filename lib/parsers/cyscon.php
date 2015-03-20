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
** Function: parse_cyscon
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_cyscon($message) {
    $source = 'CSIRT';
    $type   = 'ABUSE';

    // Read and parse report which is located in the attachment
    if(!empty($message['attachments'][1]) && $message['attachments'][1] == "SPAMVERTIZED-report.txt") {
        $report = file_get_contents($message['store'] ."/1/". $message['attachments'][1]);
        $class  = 'Compromised website';
        $type   = 'ABUSE';

    } elseif(!empty($message['attachments'][1]) && $message['attachments'][1] == "MALWARE-report.txt") {
        $report = file_get_contents($message['store'] ."/1/". $message['attachments'][1]);
        $class  = 'Compromised website';
        $type   = 'ABUSE';

    } elseif(!empty($message['attachments'][1]) && $message['attachments'][1] == "PHISH-report.txt") {
        $report = file_get_contents($message['store'] ."/1/". $message['attachments'][1]);
        $class  = 'Phishing website';
        $type   = 'ABUSE';

    } else {
        logger(LOG_ERR, __FUNCTION__ . " Unable to detect report type in message from ${source} subject ${message['subject']}");
        return false;
    }

    $report = str_replace("\r", "", $report);
    preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/',$report,$regs);
    $fields = array_combine($regs[1],$regs[2]);


    if (empty($fields['signature']) || empty($fields['ip']) || empty($fields['domain']) || empty($fields['last_seen']) || empty($fields['uri']) ) {
        logger(LOG_ERR, __FUNCTION__ . " Unable to select correct fields in message from ${source} subject ${message['subject']}");
        return false;
    } else {
        $fields['uri'] = str_replace("http://".$fields['domain'], "", $fields['uri']);
    }

    $outReport = array(
                        'source'        => $source,
                        'ip'            => $fields['ip'],
                        'domain'        => $fields['domain'],
                        'uri'           => $fields['uri'],
                        'class'         => $class,
                        'type'          => $type,
                        'timestamp'     => strtotime($fields['last_seen']),
                        'information'   => $fields
                      );

    $reportID = reportAdd($outReport);
    if (!$reportID) return false;
    if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;
}
?>
