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
** Function: parse_spamexperts
** Parameters: 
**  message(array): The message array returned from the receive_mail function
** Returns: True on parsing success (or false when it failed)
*/
function parse_spamexperts($message) {
    $outReport                  = array('source' => 'SpamExperts');
    $outReport['information']   = array();
    $outReport['type']          = 'ABUSE';

    if (
        isset($message['arf']) &&
        is_array($message['arf']) && isset($message['arf']['report'])
    ) {
        // We've got a ARF formatted message
        // The e-mail parses should have noticed the multipart elements and created a 'arf' array

        if (isset($message['arf']['headers']['from'])) {
            $outReport['information']['from'] = $message['arf']['headers']['from'];
        }
        if (isset($message['arf']['headers']['return-path'])) {
            if (!is_array($message['arf']['headers']['return-path'])) {
                $outReport['information']['return-path'] =  $message['arf']['headers']['return-path'];
            } else {
                $outReport['information']['return-path'] = $message['arf']['headers']['return-path'][0];
            }
        }
        if (isset($message['arf']['headers']['subject'])) {
            $outReport['information']['subject'] = $message['arf']['headers']['subject'];
        }
        if (isset($message['arf']['headers']['x-mailer'])) {
            $outReport['information']['x-mailer'] = $message['arf']['headers']['x-mailer'];
        }
        if (is_array($message['arf']['headers']['received'])) {
            foreach( $message['arf']['headers']['received'] as $id => $received) {
                $field = "header_line". ($id + 1);
                $outReport['information'][$field] = "received " . $received;
            }
        }

        $message['arf']['report'] = str_replace("\r", "", $message['arf']['report']);
        $match = "([\w\-]+): (.*)[ ]*\r?\n";
        preg_match_all("/${match}/m", $message['arf']['report'], $regs);

        // Fix for duplicate fields
        $new['Original-Rcpt-To'] = "";
        foreach($regs[0] as $index => $reg) {
            if ($regs[1][$index] == "Original-Rcpt-To") {
                $new['Original-Rcpt-To'] .= $regs[2][$index];
                unset($regs[1][$index]);
                unset($regs[2][$index]);
            }
        }
        $regs[1][] = 'Original-Rcpt-To';
        $regs[2][] = $new['Original-Rcpt-To'];

        $fields = array_combine($regs[1],$regs[2]);
        $outReport['information'] = array_merge($outReport['information'], $fields);

        $match = "smtp.auth=(.*)";
        preg_match("/${match}/m", $outReport['information']['Authentication-Results'], $customerCode);
        $customer = customerLookupCode($customerCode[1]);

        $outReport['customer'] = array(
                                        'Code'    => $customer['Code'],
                                        'Name'    => $customer['Name'],
                                        'Contact' => $customer['Contact'],
                                        'AutoNotify' => $customer['AutoNotify'],
                                      );

        $outReport['class']         = "SPAM";
        $outReport['ip']            = $fields['Source-IP'];
        $outReport['timestamp']     = strtotime($fields['Arrival-Date']);

        $reportID = reportAdd($outReport);
        if (!$reportID) return false;
        if(KEEP_EVIDENCE == true && $reportID !== true) { evidenceLink($message['evidenceid'], $reportID); }

    }

    logger(LOG_INFO, __FUNCTION__ . " Completed message from ${outReport['source']} subject ${message['subject']}");
    return true;
}
?>
