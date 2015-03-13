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
** Function: parse_google
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_google($message) {
    // Feed configuration
    $types = array(
                    0 => "Compromised website",
                    1 => "Phishing website",
                    2 => "Distribution website",
                  );
    // ==================

    $source         = "Google Safe Browsing";
    $type           = "ABUSE";
    $xml            = simplexml_load_string($message['body']);
    $timestamp      = strtotime($xml->attributes()->date);

    foreach($xml->list as $report){
        $list_id    = (string)$report->attributes()->type;
        $class      = $types[$list_id];

        foreach($report->url_info as $url_info) {
            $url = (string)$url_info->attributes()->url;
            $ip  = (string)$url_info->attributes()->ip;

            if (!preg_match("/((http|https)\:\/\/).*/", $url, $m)){
                $url = "http://${url}";
            }

            $url_info = parse_url($url);

            if(valid_ip($ip) != true) {
                // IP is within the URL we need

                if (valid_ip($url_info['host'])) {
                    $url_info['ip'] = $url_info['host'];
                    $url_info['domain'] = "";
                } else {
                    $url_info['ip'] = gethostbyname($url_info['host']);
                    $url_info['domain'] = $url_info['host'];
                }
            } else {
                $url_info['ip']     = $ip;
                $url_info['domain'] = $url_info['host'];
            }

            if (!isset($url_info['port']) && $url_info['scheme'] == "http") {
                $url_info['port'] = "80";
            } elseif (!isset($url_info['port']) && $url_info['scheme'] == "https") {
                $url_info['port'] = "443";
            } elseif(!isset($url_info['port'])) {
                $url_info['port'] = "";
            }

            if (!isset($url_info['path'])) {
                $url_info['path'] = "/";
            }

            $information = array(
                                 'scheme'       => $url_info['scheme'],
                                 'port'         => $url_info['port'],
                                 'domain'       => $url_info['domain'],
                                 'uri'          => $url_info['path'],
                                );

            $outReport =  array(
                                'source'        => $source,
                                'ip'            => $url_info['ip'],
                                'domain'        => $url_info['domain'],
                                'uri'           => $url_info['path'],
                                'class'         => $class,
                                'type'          => $type,
                                'timestamp'     => $timestamp,
                                'information'   => $information
                               );

            $reportID = reportAdd($outReport);
            if (!$reportID) return false;
            if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

        }
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;
}
?>
