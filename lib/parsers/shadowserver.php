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
** Function: parse_shadowserver
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_shadowserver($message) {
    $feeds = array(
                'scan_qotd'             => array (
                                                    'class'     => 'Open QOTD Server',
                                                    'fields'    => 'protocol port',
                                                    'type'      => 'INFO',
                                                 ),
                'spam_url'              => array (
                                                    'class'     => 'Spamvertised web site',
                                                    'fields'    => 'url host',
                                                    'type'      => 'ABUSE',
                                                 ),
                'scan_mssql'            => array (
                                                    'class'     => 'Open Microsoft SQL Server',
                                                    'fields'    => 'protocol port version instance_name tcp_port named_pipe response_length amplification',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_ssl_poodle'       => array (
                                                    'class'     => 'SSLv3 Vulnerable Server',
                                                    'fields'    => 'port handshake cipher_suite subject_common_name issuer_common_name cert_expiration_date issuer_organization_name',
                                                    'type'      => 'INFO',
                                                 ),
                'ssl_scan'              => array (
                                                    'class'     => 'SSLv3 Vulnerable Server',
                                                    'fields'    => 'port handshake cipher_suite subject_common_name issuer_common_name cert_expiration_date issuer_organization_name',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_ssl_freak'        => array (
                                                    'class'     => 'FREAK Vulnerable Server',
                                                    'fields'    => 'port handshake cipher_suite freak_cipher_suite subject_common_name issuer_common_name cert_expiration_date',
                                                    'type'      => 'INFO',
                                                 ),
                'cc_ip'                 => array (
                                                    'class'     => 'Command and control server',
                                                    'fields'    => 'port channel',
                                                    'type'      => 'ABUSE',
                                                 ),
                'scan_ntpmonitor'       => array (
                                                    'class'     => 'Possible DDOS sending NTP Server',
                                                    'fields'    => 'protocol port hostname packets size',
                                                    'type'      => 'ABUSE',
                                                 ),
                'compromised_website'   => array (
                                                    'class'     => 'Compromised website',
                                                    'fields'    => 'http_host category tag redirect_target',
                                                    'type'      => 'ABUSE',
                                                 ),
                'cwsandbox_url'         => array (
                                                    'class'     => 'Malware infection',
                                                    'fields'    => 'md5hash url user_agent host method',
                                                    'type'      => 'ABUSE',
                                                 ),
                'sinkhole_http_drone'   => array (
                                                    'class'     => 'Botnet infection',
                                                    'fields'    => 'type url http_agent src_port dst_ip dst_port',
                                                    'type'      => 'ABUSE',
                                                 ),
                'microsoft_sinkhole'    => array (
                                                    'class'     => 'Botnet infection',
                                                    'fields'    => 'type url http_agent src_port dst_ip dst_port',
                                                    'type'      => 'ABUSE',
                                                 ),
                'botnet_drone'          => array (
                                                    'class'     => 'Botnet infection',
                                                    'fields'    => 'infection url agent cc cc_port cc_dns',
                                                    'type'      => 'ABUSE',
                                                 ),
                'dns_openresolver'      => array (
                                                    'class'     => 'Open DNS Resolver',
                                                    'fields'    => 'protocol port min_amplification dns_version',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_ntp'              => array (
                                                    'class'     => 'Open NTP Server',
                                                    'fields'    => 'clock error frequency peer refid reftime stratum system',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_snmp'             => array (
                                                    'class'     => 'Open SNMP Server',
                                                    'fields'    => 'sysdesc sysname version',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_netbios'          => array (
                                                    'class'     => 'Open Netbios Server',
                                                    'fields'    => 'mac_address workgroup machine_name username',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_ssdp'             => array (
                                                    'class'     => 'Open SSDP Server',
                                                    'fields'    => 'systime location server unique_service_name',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_chargen'          => array (
                                                    'class'     => 'Open Chargen Server',
                                                    'fields'    => 'protocol port size',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_ipmi'             => array (
                                                    'class'     => 'Open IPMI Server',
                                                    'fields'    => 'port ipmi_version none_auth md2_auth md5_auth passkey_auth oem_auth defaultkg permessage_auth userlevel_auth usernames nulluser anon_login',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_net_pmp'          => array ( //Apparently shadowserver used this one in error, keeping it for parsing history
                                                    'class'     => 'Open NAT_PMP Server',
                                                    'fields'    => 'protocol port version uptime',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_nat_pmp'          => array (
                                                    'class'     => 'Open NAT_PMP Server',
                                                    'fields'    => 'protocol port version uptime',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_redis'            => array (
                                                    'class'     => 'Open REDIS Server',
                                                    'fields'    => 'protocol port version mode os process_id',
                                                    'type'      => 'INFO',
                                                 ),
                'wiki_file'             => array ( //Apparently shadowserver used this one in error, keeping it for parsing history
                                                    'class'     => 'Open MemCached Server',
                                                    'fields'    => 'protocol port version uptime curr_connections total_connections',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_memcached'        => array (
                                                    'class'     => 'Open MemCached Server',
                                                    'fields'    => 'protocol port version uptime curr_connections total_connections',
                                                    'type'      => 'INFO',
                                                 ),
                'scan_mongodb'        => array (
                                                    'class'     => 'Open MongoDB Server',
                                                    'fields'    => 'protocol port version sysinfo visible_databases',
                                                    'type'      => 'INFO',
                                                 ),
              );

    // ==================

    $source = "Shadowserver";

    // Ignore some feeds if an ignore list exists in the configuration
    $feed_ignore = (defined('SHADOWSERVER_IGNORE'))?explode(',',str_replace(' ','',SHADOWSERVER_IGNORE)):array();

    foreach( $message['attachments'] as $attachmentID => $attachment) {
        preg_match("~(?:\d{4})-(?:\d{2})-(?:\d{2})-(.*)-[^\-]+-[^\-]+.csv~i", $attachment, $feed);
        $feed   = $feed[1];

        if (!isset($feeds[$feed])) {
            //Autodetect of classification failed - this is a config error!
            logger(LOG_ERR, __FUNCTION__ . " A configuration error was detected. An unconfigured feed ${feed} was selected for parsing");
            logger(LOG_WARNING, __FUNCTION__ ." FAILED message from ${source} subject ${message['subject']}");
            return false;
        } else if (in_array($feed,$feed_ignore)) {
            logger(LOG_INFO, __FUNCTION__ ." IGNORING message from ${source} subject ${message['subject']}");
            return true;
        }

        $class   = $feeds[$feed]['class'];
        $type    = $feeds[$feed]['type'];
        $fields  = explode(" ", $feeds[$feed]['fields']);
        $reports = csv_to_array("${message['store']}/${attachmentID}/${attachment}");

        if(!is_array($reports)) {
            logger(LOG_ERR, __FUNCTION__ . " A parser error was detected. Will not try to continue to parse this e-mail");
            logger(LOG_WARNING, __FUNCTION__ . " FAILED message from ${source} subject ${message['subject']}");
            return false;
        }

        foreach($reports as $id => $report) {
            $information = array();
            foreach($fields as $field) {
                if (!empty($report[$field])) {
                    $information[$field] = $report[$field];
                }
            }

            $outReport = array(  
                                'source'        => $source,
                                'ip'            => $report['ip'], 
                                'class'         => $class,
                                'type'          => $type,
                                'timestamp'     => strtotime($report['timestamp']),
                                'information'   => $information
                              );

            //These reports have a domain, which we want to register seperatly
            if($feed == "spam_url") {
                $url_info = parse_url($report['url']);

                $outReport['domain'] = $url_info['host'];
                $outReport['uri'] = $url_info['path'];
            }
            if($feed == "ssl_scan") {
                $outReport['domain'] = $report['subject_common_name'];
                $outReport['uri'] = "/";
            }
            if($feed == "compromised_website") {
                $outReport['domain'] = $report['http_host'];
                $outReport['uri'] = "/";
            }
            if($feed == "botnet_drone") {
                $outReport['domain'] = $report['cc_dns'];
                $outReport['uri'] = str_replace("//", "/", "/" . $report['url']);
            }

            $reportID = reportAdd($outReport);
            if (!$reportID) return false;
            if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

        }
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;
}
?>
