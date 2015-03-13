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
** Function: parse_blocklist_de
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_blocklist_de($message) {

    $source = "Blocklist.DE";

    // Fetched from schema at https://www.blocklist.de/downloads/schema/info_0.1.1.json
    $typeMap = array(
        'login-attack'=>'Login attack',
        'info'=>'Informational',
        'harvesting'=>'Harvesting',
        'hack-attack'=>'Hack attack',
        'regbot'=>'Register bot',
        'ircbot'=>'IRC bot',
        'badbot'=>'Bad bot',
        'ddos'=>'DDoS attack',
        'apacheddos'=>'DDoS attack (apache)',
        'reg-bot'=>'Register bot',
        'bad-bot'=>'Bad bot',
        'irc-bot'=>'IRC bot',
    );

    if (!empty($message['store']) && !empty($message['attachments'])) {

        // Report should be named 'report.txt'
        foreach ($message['attachments'] as $k => $file) {
               if ($file == 'report.txt') break;
        }

        if (empty($file)) {
            logger(LOG_ERR, __FUNCTION__ . " Unable to find abuse report in message from ${source} subject ${message['subject']}");
            return false;
        }

        // Read and parse report
        $report = file_get_contents($message['store'].'/'.$k.'/'.$file);
        preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/',$report,$regs);
        $fields = array_combine($regs[1],$regs[2]);

        if (!array_key_exists($fields['Report-Type'],$typeMap)) {
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

    } else {
        logger(LOG_ERR, __FUNCTION__ . " Unable to parse message from ${source} subject ${message['subject']}");
    }
}
?>
