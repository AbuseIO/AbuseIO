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
** Function: parse_webiron
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_webiron($message) {

    $source = "Webiron.com";

    // Fetched from schema at https://www.webiron.com/arf/malware-attack.json
    $typeMap = array(
        'web-attack'=>'DDoS attack',
    );

    if (!empty($message['store']) && !empty($message['attachments'])) {

        // Reports are named 'arf_reportX.txt', mail could contain multiple reports
        foreach ($message['attachments'] as $k => $file) {
               if (preg_match('/arf_report[0-9]+/',$file)) $files[$k] = $file;
        }

        if (empty($files)) {
            logger(LOG_ERR, __FUNCTION__ . " Unable to find abuse report in message from ${source} subject ${message['subject']}");
            return false;
        }

        // Read and parse reports
        foreach ($files as $k => $file) {

            $report = file_get_contents($message['store'].'/'.$k.'/'.$file);
            preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/',$report,$regs);
            $fields = array_combine($regs[1],$regs[2]);

            if (!array_key_exists($fields['Report-Type'],$typeMap)) {
                logger(LOG_ERR, __FUNCTION__ . " Unable to detect report type in message from ${source} subject ${message['subject']}");
            }

            $outReport = array(
                                'source'        => $source,
                                'ip'            => $fields['Source'],
                                'class'         => $typeMap[$fields['Report-Type']],
                                'type'          => 'ABUSE',
                                'timestamp'     => strtotime(str_replace('\'','',$fields['Date'])),
                                'information'   => $fields
                              );

            $reportID = reportAdd($outReport);
            if (!$reportID) return false;
            if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

        }

        logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
        return true;

    // This is possibly an inline abuse report
    } else if (preg_match('/Offending\/Source IP:[ ]+([0-9\.]+)\n/',$message['body'], $regs)) {

        // Parse IP and all report lines from body
        $ip = $regs[1];
        preg_match_all('/  - ([^:]+): ([^\n]+)\n/',$message['body'],$regs);

        if (empty($regs)) {
            logger(LOG_ERR, __FUNCTION__ . " Unable to parse message from ${source} subject ${message['subject']}");
            return false;
        }

        $fields = array_combine($regs[1],$regs[2]);
        $outReport = array(
            'source'        => $source,
            'ip'            => $ip,
            'class'         => 'Botnet infection',
            'type'          => 'ABUSE',
            'timestamp'     => strtotime($fields['Time']),
            'information'   => $fields
        );

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }
        return true;

    } else {
        logger(LOG_ERR, __FUNCTION__ . " Unable to parse message from ${source} subject ${message['subject']}");
    }
}
?>
