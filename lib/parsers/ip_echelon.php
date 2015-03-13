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
** Function: parse_ip_echelon
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_ip_echelon($message) {

    $source = 'IP-Echelon';
    $type = 'ABUSE';

    if (
        !empty($message['store']) && !empty($message['attachments']) &&
        $xml = simplexml_load_string(file_get_contents($message['store'].'/'.key($message['attachments']).'/'.array_shift($message['attachments'])))

    ) {
        $information = array(
                                'type'          => (string)$xml->Source->Type,
                                'port'          => (string)$xml->Source->Port,
                                'number_files'  => (string)$xml->Source->Number_Files,
                                'complainant'   => (string)$xml->Complainant->Entity,
                            );

        $outReport   = array(
                                'source'        => $source,
                                'ip'            => (string)$xml->Source->IP_Address,
                                'class'         => 'Copyright Infringement',
                                'type'          => $type,
                                'timestamp'     => strtotime($xml->Source->TimeStamp),
                                'information'   => $information,
        );

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

        logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
        return $reportID;

    } else {
        logger(LOG_ERR, __FUNCTION__." Unable to parse XML ${source} subject ${message['subject']}");
        return false;
    }
}
?>
