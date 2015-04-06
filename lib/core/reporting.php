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
*******************************************************************************
*
* Core reporting / event handling related functions
*
******************************************************************************/

/*
** Function: reportAdd
** Parameters: 
**  report(array):
**   source(string)
**   ip(string)
**   class(string)
**   type(string)
**   timestamp(int)
**   information(array)
**   customer(array): optional
** Returns: 
**  (int): mysql insert ID on success
**  (boolean): on failure
*/
function reportAdd($report) {
    if (!is_array($report)) {
        return false;
    } else {
        extract($report);
    }

    if (!isset($ip) || !isset($source) || !isset($class) || !isset($type) || !isset($timestamp)) {
        logger(LOG_WARNING, __FUNCTION__ . " was called with not enough arguments in the array");
        return false;
    }
    if(date('Y', $timestamp) < 2013 || strtotime(date('d-m-Y H:i:s',$timestamp)) !== (int)$timestamp) {
        logger(LOG_WARNING, __FUNCTION__ . " was called with an incorrect timestamp (${timestamp})");
        return false;
    }

    if (!isset($domain)) {
        $domain = '';
    } elseif(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $domain = $regs['domain'];
    } else {
        // Its fine as it is
    }

    $select  = "SELECT * FROM Reports";
    $filteradd = "";
    if (isset($customer) && is_array($customer) && !empty($customer['Code']) && !empty($customer['AutoNotify'])) {
        $filteradd = "AND CustomerCode='${customer['Code']}'";
    }
    $matchperiod = $timestamp - strtotime(REPORT_MATCHING . " ago");
    $filter  = "WHERE IP='${ip}' AND Domain LIKE '%${domain}%' AND Source='${source}' AND Class='${class}' AND LastSeen > '${matchperiod}' AND Status != 'CLOSED' ${filteradd} ORDER BY LastSeen DESC LIMIT 1;";
    $query   = "${select} ${filter}";
    $count   = _mysqli_num_rows($query);

    if (!isset($domain)) {
        $domain = "";
    }
    if (!isset($uri)) {
        $uri    = "";
    }

    if ($count > 1) {
        // This should never happen
        return false;

    } elseif ($count === 1) {
        $result = _mysqli_fetch($query);
        if (empty($result)) return false;

        $row = $result[0];
        if ($row['LastSeen'] == $timestamp) {
            logger(LOG_WARNING, __FUNCTION__ . " by $source ip $ip class $class seen " . date("d-m-Y H:i:s",$timestamp) . " is DUPLICATE!");
            return true;
        } elseif ($row['LastSeen'] >= $timestamp) {
            logger(LOG_WARNING, __FUNCTION__ . " by $source ip $ip class $class seen " . date("d-m-Y H:i:s",$timestamp) . " is OBSOLETE!");
            return true;
        } else {
            // TODO when updating not only update timestamp but also match information if there is something new to add
            $update = "UPDATE Reports SET LastSeen='${timestamp}', ReportCount='".($row['ReportCount'] + 1)."'";
            $query  = "${update} ${filter}";
            if (_mysqli_query($query, "")) {
                if(function_exists('custom_notifier')) {
                    logger(LOG_DEBUG, __FUNCTION__ . " is calling custom_notifier for UPDATED notification");
                    $report['customer'] = array(
                                                'Code' => $row['CustomerCode'],
                                                'Name' => $row['CustomerName'],
                                                'Contact' => $row['CustomerContact'],
                                                'AutoNotify' => $row['AutoNotify'],
                                               );
                    $report['state']    = 'UPDATED';
                    custom_notifier($report);
                }

                logger(LOG_DEBUG, __FUNCTION__ . " by $source ip $ip class $class seen " . date("d-m-Y H:i:s",$timestamp) . " is UPDATED");
                return $row['ID'];
            }
            return false;
        }
    } else {
        if (!isset($customer) || !is_array($customer)) {
            $customer = customerLookupIP($ip);
        } else {
            if(empty($customer['Code'])) {
                logger(LOG_ERR, __FUNCTION__ . " was incorrectly called with empty customer information");
                return false;
            }
        }

        $query = "INSERT INTO Reports (
                                        Source, 
                                        IP, 
                                        Domain, 
                                        URI, 
                                        FirstSeen, 
                                        LastSeen, 
                                        Information, 
                                        Class,
                                        Type,
                                        CustomerCode, 
                                        CustomerName,
                                        CustomerContact, 
                                        CustomerResolved,
                                        CustomerIgnored,
                                        Status,
                                        AutoNotify,
                                        NotifiedCount, 
                                        ReportCount,
                                        LastNotifyReportCount,
                                        LastNotifyTimestamp
                            ) VALUES (
                                        \"${source}\", 
                                        \"${ip}\", 
                                        \"${domain}\", 
                                        \"${uri}\", 
                                        \"${timestamp}\", 
                                        \"${timestamp}\", 
                                        \"" . mysql_escape_string(@json_encode($information)) . "\", 
                                        \"${class}\", 
                                        \"${type}\",
                                        \"${customer['Code']}\", 
                                        \"${customer['Name']}\",
                                        \"${customer['Contact']}\", 
                                        \"0\", 
                                        \"0\",
                                        \"OPEN\",
                                        \"".((empty($customer['AutoNotify']))?0:1)."\",
                                        \"0\", 
                                        \"1\",
                                        \"0\",
                                        \"0\"
                            );";

        $result = _mysqli_query($query);
        if ($result) {
            if(function_exists('custom_notifier')) {
                logger(LOG_DEBUG, __FUNCTION__ . " is calling custom_notifier for NEW notification");
                $report['customer'] = $customer;
                $report['state']    = 'NEW';
                custom_notifier($report);
            }

            logger(LOG_DEBUG, __FUNCTION__ . " by $source ip $ip class $class seen " . date("d-m-Y H:i:s",$timestamp));
            return $result;
        }
        return false;

    }
}


/*
** Function: reportList
** Parameters: 
**  filter(string): SQL WHERE Condition
** Returns: 
**  (array): MySQL rows with fields
*/
function reportList($filter) {
    $reports = array();
    $query = "SELECT * FROM Reports WHERE 1 ${filter}";

    $reports = _mysqli_fetch($query);
    return $reports;
}


/*
** Function: reportCount
** Parameters: 
**  filter(string): SQL WHERE Condition
** Returns:
**  (int): mysql row count
*/
function reportCount($filter) {
    $reports = array();
    $query = "SELECT COUNT(*) as Count FROM Reports WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);
    if (!empty($reports[0]['Count'])) return $reports[0]['Count'];
    return 0;
}


/*
** Function: reportGet
** Parameters: 
**  id(int): Report.ID
** Returns: 
**  (array)
*/
function reportGet($id) {
    $reports = array();

    if (!is_numeric($id)) {
        return false;
    }

    $filter  = "AND ID='${id}'";
    $query   = "SELECT * FROM Reports WHERE 1 ${filter}";

    $report = _mysqli_fetch($query);

    if (isset($report[0])) {
        return $report[0];
    } else {
        return false;
    }
}


/*
** Function: reportSummary
** Parameters: 
**  period(int):
** Returns: 
**  (array): MySQL rows with fields
*/
function reportSummary($period) {
    $summary = array();

    $query  = "SELECT Class, count(*) AS Count FROM Reports WHERE 1 AND LastSeen > '".strtotime($period . " days ago")."' GROUP BY Class";

    $rows = _mysqli_fetch($query);

    foreach($rows as $id => $row) {
        $summary[$row['Class']] = $row['Count'];
    }

    return $summary;
}


/*
** Description: return an array with every IP there are reports for within a period
** Function: reportIps
** Parameters: 
**  period(int): timestamp
** Returns: 
**  (array)
*/
function reportIps($period) {
    $summary = array();

    $query  = "SELECT DISTINCT Ip FROM Reports WHERE LastSeen > $period";

    if ($ips = _mysqli_fetch($query)) {
        $ret = array();
        foreach ($ips as $ip) $ret[] = $ip['Ip'];
        return $ret;
    }

    return false;
}


/*
** Function: reportMerge
** Parameters: 
** Returns: 
*/
function reportMerge() {

}


/*
** Function: reportResolved
** Parameters: 
**  ticket(int):
** Returns: 
**  (boolean):
*/
function reportResolved($ticket) {
    if(!is_numeric($ticket)) {
        return false;
    }

    $query = "Update Reports SET CustomerResolved = '1', CustomerIgnored = '0' WHERE 1 AND ID = '${ticket}'";

    $result = _mysqli_query($query, "");

    return $result;
}


/*
** Function: reportIgnored
** Parameters: 
**  ticket(int):
** Returns:
**  (boolean):
*/
function reportIgnored($ticket) {
    if(!is_numeric($ticket)) {
        return false;
    }

    $query = "Update Reports SET CustomerIgnored = '1', CustomerResolved = '0' WHERE 1 AND ID = '${ticket}'";

    $result = _mysqli_query($query, "");

    return $result;
}


/*
** Function: reportClosed
** Parameters:
**  ticket(int):
** Returns:
**  (boolean):
*/
function reportClosed($ticket) {
    if(!is_numeric($ticket)) {
        return false;
    }

    $query = "Update Reports SET Status = 'CLOSED' WHERE 1 AND ID = '${ticket}'";

    $result = _mysqli_query($query, "");

    return $result;
}



/*
** Description: Do some housekeeping on reports, like closing old tickets or merging
**              based on the etc/settings configuration
** Function: reportHousekeeping
** Parameters: None
** Returns: 
**  (boolean):
*/
function reportHousekeeping() {
    $filter  = "";
    $reports = reportList($filter);

    foreach($reports as $id => $report) {

        // Close old cases
        if ($report['LastSeen'] < strtotime(REPORT_CLOSING . "ago") ) {
            reportClosed($report['ID']);
        }
    }

    return true;
}


/*
** Function: ReportContactupdate
** Parameters:
**  ticket(int):
** Returns:
**  (boolean):
*/
function ReportContactupdate($ticket) {
    if(!is_numeric($ticket)) {
        return false;
    }

    $report = reportGet($ticket);

    $customer = customerLookupIP($report['IP']);

    if (isset($customer['Code']) && $customer['Code'] != $result['CustomerCode']) {
        $query = "UPDATE `Reports` SET CustomerCode='${customer['Code']}', CustomerName='${customer['Name']}', CustomerContact='${customer['Contact']}' WHERE ID='${ticket}';";
        _mysqli_query($query, "");

        return true;
    } else {
        return false;
    }
}


/*
** Function: reportNotified
** Parameters:
**  ticket(int):
** Returns:
**  (boolean):
*/
function reportNotified($ticket) {
    if(!is_numeric($ticket)) {
        return false;
    }

    // Subfunction for reportNotification which can be called when a notifier
    // successfully send out the notification to mark the ticket as notified
    //
    // Set the notifyCount + 1, Set the LastNotifyReportCount to ReportCount
    $query = "Update Reports SET LastNotifyReportCount = ReportCount, NotifiedCount = NotifiedCount+1, LastNotifyTimestamp = '".time()."' WHERE 1 AND ID = '${ticket}'";

    $result = _mysqli_query($query, "");

    return $result;    
}


/*
** Description: sends out notifications based on a filter
** Function: reportSend
** Parameters: 
**  filter(array): (with one of the following elements:)
**   Ticket(int):       Send out for a specific ticket
**   IP(string):        Send out for a specific IP
**   Customer(string):  Send out for a specific customer
**   All(boolean):      Send out everthing thats considered unhandled
**   Days(int):         Optional addition a limition of amount of days in the past
** Returns: 
**  (boolean):
*/
function reportSend($filter) {

    if (!isset($filter) && !is_array($filter)) {
        return false;
    }
    if (empty($filter['Ticket']) && empty($filter['IP']) && empty($filter['Customer']) && empty($filter['All'])) {
        return false;
    }

    if (!defined('NOTIFICATIONS') && NOTIFICATIONS != true && !is_file(APP.NOTIFICATION_TEMPLATE)){
        logger(LOG_DEBUG, "Notifier - Is not enabled or template is missing");
        return false;
    } else {
        $template = file_get_contents(APP.NOTIFICATION_TEMPLATE);
    }

    $counter= 0;

    logger(LOG_DEBUG, "Notifier - Is starting a run");

    // Collect reports - return all the data so you can decide what to put
    // in the customer mail. format: array($reports[CustomerCode][$i][$report_elements])
    $allreports = reportNotification($filter);

    $typemsg = array(
                        'INFO' => 'Information message, we strongly advice this matter to be resolved',
                        'ABUSE' => 'Abuse message, we require you to take direct action to resolve this matter',
                        'ESCALATION' => 'Escalation message, we are implementing measures to resolve this matter', 
                    );

    foreach($allreports as $customerCode => $reports) {
        $count = count($reports);

        $blocks = "";
        foreach($reports as $id => $report) {
            $block = array();
            $report['Information'] = json_decode($report['Information']);

            if (SELF_HELP_URL != "") {
                $token = md5("${report['ID']}${report['IP']}${report['Class']}");
                $selfHelpLink = SELF_HELP_URL . "?id=${report['ID']}&token=" . $token;
            } else {
                $selfHelpLink = "";
            }

            $block[] = "";
            $block[] = "Ticket #${report['ID']}: Report for IP address ${report['IP']} (${report['Class']})";
            $block[] = "Category: ". $typemsg[$report['Type']];
            $block[] = "Report date: ".date('Y-m-d H:i',$report['LastSeen']);
            $block[] = "Report count: ".$report['ReportCount'];
            $block[] = "Source: ${report['Source']}";
            if (!empty($selfHelpLink)) $block[] = "Reply or help: " . $selfHelpLink;
            if (!empty($report['Information'])) {
                $block[] = "Report information:";
                if(isset($report['Information']->Domain)) $block[] = "  - domain: " . str_replace('.','[.]',$report['Information']->Domain);
                if(isset($report['Information']->URI)) $block[] = "  - uri/path: " . $report['Information']->URI;
                $report['Information']->Address = $report['IP'];
                foreach($report['Information'] as $field => $value) {
                    // If the value contains a domain name, escape it so spam filters won't flag this abuse report
                    if (in_array($field,array('cc_dns','domain','host','url','http_host'))) $value = str_replace('.','[.]',$value);
                    $block[] = "  - ${field}: ${value}";
                }
            }
            $block[] = "\n";
            $blocks .= implode("\n", $block);
        }

        if (defined('TESTMODE') && TESTMODE === true) {
            $to =           NOTIFICATIONS_FROM_ADDRESS;
        } else {
            $to             = $report['CustomerContact'];
        }
        $email              = $template;
        $subject            = '['.date('Y-m-d').'] Notification of (possible) abuse';
        $email              = str_replace("<<COUNT>>", $count, $email);
        $email              = str_replace("<<BOXES>>", $blocks, $email);

        // Validate all the email addresses in the TO field
        if (!empty($to) && strpos(",", $to) !== false) {
            if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                $validated = true;
            } else {
                $validated = false;
            }
        } else {
            $to = str_replace(" ", "", $to);
            $addresses = explode(",", $to);
            foreach($addresses as $address) {
                if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
                    $validated = true;
                } else {
                    $validated = false;
                }
            }
        }

        $headers   = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/plain; charset=iso-8859-1";
        $headers[] = "From: " . NOTIFICATIONS_FROM_NAME . " <" . NOTIFICATIONS_FROM_ADDRESS . ">";
        $headers[] = "Reply-To: " . NOTIFICATIONS_FROM_NAME . " <" . NOTIFICATIONS_FROM_ADDRESS . ">";
        $headers[] = "X-Mailer: AbuseIO/".VERSION;

        if (defined('NOTIFICATIONS_BCC')) {
            $headers[] = "BCC: " . NOTIFICATIONS_BCC;
        }

        if ($validated) {
            if(mail($to, $subject, $email, implode("\r\n", $headers))) {
                logger(LOG_DEBUG, "Notifier - Successfully sent ${count} reports by notification to ${to}");
                $counter++;
                foreach($reports as $id => $report) {
                    //Mark the report as notified:
                    reportNotified($report['ID']);
                }
            } else {
                logger(LOG_ERR, "Notifier - Failed sending mail to ${to} MTA returned false");
                return false;
            }
        } else {
            logger(LOG_ERR, "Notifier - Failed sending mail to ${to} as the addres is incorrectly formatted");
            return false;
        }
    }

    logger(LOG_DEBUG, "Notifier - Completed and has sent out {$counter} notifications");
    return true;
}


/*
** Function: reportNotification
** Parameters: 
**  filter(string): SQL WHERE Condition
** Returns: 
**  (array): Mysql rows with fields
*/
function reportNotification($filter) {
    // First we will create an selection
    // Ticket, IP, Customer reports are hooks from the GUI/CLI and will e-mail
    // Everything thats not resolved and will not look at LastNotifyReportCount
    //
    // When sending out all reports, we look at the LastNotifyReportCount to see
    // if new reports came in and the count does not match and the customer has
    // the flag AutoNotify enabled.
    //
    // This will return an array of rows to be notified. so we can combine 
    // all items per customer

    $data  = array();
    $query = "SELECT * FROM Reports WHERE 1 AND Status != 'CLOSED' ";

    if (!is_array($filter)) {
        return false;
    } elseif (isset($filter['Ticket'])) {
        // Notify for this ticket only
        $query .= "AND ID = '${filter['Ticket']}' ";

    } elseif (isset($filter['IP'])) {
        // Notify everything about this IP
        $query .= "AND IP = '${filter['IP']}' ";

    } elseif (isset($filter['Customer'])) {
        // Notify everything about this Customer(code)
        $query .= "AND CustomerCode = '${filter['Customer']}' ";

    } elseif (isset($filter['All'])) {
        $query .= "AND AutoNotify = '1' ";

    } else {
        return false;
    }

    if (isset($filter['Days'])) {
        $from = time()-(86400*$filter['Days']);
        $query .= "AND LastSeen >= $from ";
    }

    $interval_info_after  = strtotime(NOTIFICATIONS_INFO_INTERVAL . " ago");
    $interval_abuse_after = strtotime(NOTIFICATIONS_ABUSE_INTERVAL . " ago");

    foreach(_mysqli_fetch($query) as $id => $row) {
        if(isset($filter['All']) && $row['ReportCount'] == $row['LastNotifyReportCount']) {
            // Already notified, nothing new to report

        } elseif($row['CustomerCode'] == "UNDEF") {
            // Customer is not found, therefore we cannot send notifications

        } elseif($row['CustomerIgnored'] == 1) {
            // Customer does not want any more notifications from this report

        } elseif(isset($filter['All']) && $row['ReportCount'] != $row['LastNotifyReportCount'] && $row['AutoNotify'] == '1') {
            // The 'all' filter is called by the cronned notifier for automatic notifications
            // It will check based on the NOTIFICATION_INFO_INTERVAL and NOTIFICATION_ABUSE_INTERVAL is a case is to be 
            // sent out. However if the case was marked as resolved it should always send out the notification again and
            // unset the customerResolved flag. Also the customers AutoNotify must be enabled for notifications to be send.
            if ($row['Type'] == 'INFO' && $row['LastNotifyTimestamp'] <= $interval_info_after) {
                $data[$row['CustomerCode']][] = $row;

            } elseif ($row['Type'] == 'ABUSE' && $row['LastNotifyTimestamp'] <= $interval_abuse_after) {
                $data[$row['CustomerCode']][] = $row;

            } elseif ($row['Type'] == 'ALERT') {
                $data[$row['CustomerCode']][] = $row;

            } else {
                // Skip this report for notification

            }

        } else {
            $data[$row['CustomerCode']][] = $row;

        }
    }

    return $data;
}


/*
** Function: reportNotification
** Parameters:
**  lang(string): CC code of language
**  class(string): name of the class an infotext is being collected
** Returns:
**  (string): html blob with class information
*/
function infotextGet($lang, $class) {
    if (strlen($lang) != 2) { 
        return false; 
    }

    $classfile = str_replace(" ", "_", $class).".html";

    if(file_exists(APP . "/www/ash/infotext/" . $classfile)) {
        return file_get_contents(APP . "/www/ash/infotext/" . $classfile);

    } elseif (file_exists(APP . "/www/ash/infotext/default/${lang}/${classfile}")) {
        return file_get_contents(APP . "/www/ash/infotext/default/${lang}/${classfile}");

    } else { 
        return false ;
    }

}
?>
