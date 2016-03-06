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
** Parser       : Feedbackloop
** Description  : The parser will read and parse feedbackloop mails
*/

/*
** Function: parse_feedbackloop
** Parameters:
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_feedbackloop($message) {

    $sourceMap = array(
        '/scomp@aol.net/'                               => 'AOL',
        '/feedbackloop@feedback.bluetie.com/'           => 'BlueTie',
        '/feedbackloop@comcastfbl.senderscore.net/'     => 'Comcast',
        '/feedbackloop@fbl.cox.net/'                    => 'COX',
        '/feedbackloop@fbl.fastmail.com/'               => 'FastMail',
        '/feedbackloop@fbl.hostedemail.com/'            => 'OpenSRS',
        '/feedbackloop@fbl.apps.rackspace.com/'         => 'Rackspace',
        '/feedbackloop@feedback.postmaster.rr.com/'     => 'Time Warner Cable',
        '/feedbackloop@fbl.synacor.com/'                => 'Synacor',
        '/feedbackloop@fbl.usa.net/'                    => 'USANET',
        '/feedbackloop@fbl.zoho.com/'                   => 'ZOHO',
        '/feedback@arf.mail.yahoo.com/'                 => 'Yahoo',
        '/feedbackloop@fbl.xs4all.net/'                 => 'XS4ALL',
    );

    $source = null;
    foreach ($sourceMap as $regex => $s) {
        if (preg_match($regex, $message['from'])) {
            $source = $s;
            break;
        }
    }

    if (!isset($message['arf']['report'])) {
        logger(LOG_ERR, __FUNCTION__ . " A parser error was detected. Will not try to continue to parse this e-mail");
        logger(LOG_WARNING, __FUNCTION__ . " FAILED message from ${source} subject ${message['subject']}");
        return false;
    }

    $information = array();

    if (preg_match('/Source\-IP\: ([0-9a-fA-F\.\:]+)/', $message['arf']['report'], $matches)) {
        $src_ip = $matches[1];
    } else {
        logger(LOG_ERR, __FUNCTION__ . " No source ipaddress found. Will not try to continue to parse this e-mail");
        return false;
    }

    if (preg_match('/Arrival\-Date\: ([a-zA-Z0-9, :+]+)/', $message['arf']['report'], $matches)) {
        $timestamp = strtotime($matches[1]);
    } else {
        logger(LOG_ERR, __FUNCTION__ . " No date/time of incident found. Will not try to continue to parse this e-mail");
        return false;
    }

    if (preg_match('/Original\-Rcpt\-To\: ([^\n]+)/', $message['arf']['report'], $matches)) {
        $information['Original Recipient To'] = $matches[1];
    }

    if (preg_match('/Original\-Mail\-From\: ([^\n]+)/', $message['arf']['report'], $matches)) {
        $information['Original Mail From'] = $matches[1];
    }

    if (preg_match('/Reported\-Domain\: ([^\n]+)/', $message['arf']['report'], $matches)) {
        $information['Reported domain'] = $matches[1];
        if (is_null($source)) {
            $source = $matches[1];
        }
    }

    $outReport = array(
        'source'        => $source,
        'ip'            => $src_ip,
        'class'         => 'SPAM',
        'type'          => 'ABUSE',
        'timestamp'     => $timestamp,
        'information'   => $information,
    );

    $reportID = reportAdd($outReport);
    if (!$reportID) {
        return false;
    }
    if (KEEP_EVIDENCE == true && $reportID !== true) {
        evidenceLink($message['evidenceid'], $reportID);
    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${source} subject ${message['subject']}");
    return true;
}
?>
