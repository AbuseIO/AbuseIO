<?php
/*
    Function description
*/
function reportAdd($report) {
    // Array should minimally contain $source(string), $ip(string), $class(string), $type(string), $timestamp(int), $information(array)
    if (!is_array($report)) {
        return false;
    } else {
        extract($report);
    }

    if (!isset($ip) || !isset($source) || !isset($class) || !isset($type) || !isset($timestamp)) {
        logger(LOG_WARNING, __FUNCTION__ . " was called with not enough arguments in the array");
        return false;
    }
    if (!isset($domain)) {
        $domain = '';
    } elseif(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $domain = $regs['domain'];
    } else {
        // Its fine as it is
    }

    $select = "SELECT * FROM Reports";
    $filter = "WHERE IP='${ip}' AND Domain LIKE '%${domain}%' AND Source='${source}' AND Class='${class}' AND LastSeen > '".($timestamp-(86400*7))."' ORDER BY LastSeen DESC LIMIT 1;";
    $query  = "${select} ${filter}";
    $count  = _mysqli_num_rows($query);

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
                logger(LOG_DEBUG, __FUNCTION__ . " by $source ip $ip class $class seen " . date("d-m-Y H:i:s",$timestamp) . " is UPDATED");
                return $row['ID'];
            }
            return false;
        }
    } else {
        $customer = CustomerLookup($ip);

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
                                        AutoNotify,
                                        NotifiedCount, 
                                        ReportCount,
                                        LastNotifyReportCount
                            ) VALUES (
                                        \"${source}\", 
                                        \"${ip}\", 
                                        \"${domain}\", 
                                        \"${uri}\", 
                                        \"${timestamp}\", 
                                        \"${timestamp}\", 
                                        \"" . mysql_escape_string(json_encode($information)) . "\", 
                                        \"${class}\", 
                                        \"${type}\",
                                        \"${customer['Code']}\", 
                                        \"${customer['Name']}\",
                                        \"${customer['Contact']}\", 
                                        \"0\", 
                                        \"0\",
                                        \"".((empty($customer['AutoNotify']))?0:1)."\",
                                        \"0\", 
                                        \"1\",
                                        \"0\"
                            );";

        $result = _mysqli_query($query);
        if ($result) {
            logger(LOG_DEBUG, __FUNCTION__ . " by $source ip $ip class $class seen " . date("d-m-Y H:i:s",$timestamp));
            return $result;
        }
        return false;

    }
}


/*
    Function description
*/
function reportList($filter) {
    $reports = array();
    $query = "SELECT * FROM Reports WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);
    return $reports;
}


/*
    Function description
*/
function reportCount($filter) {
    $reports = array();
    $query = "SELECT COUNT(*) as Count FROM Reports WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);
    if (!empty($reports[0]['Count'])) return $reports[0]['Count'];
    return 0;
}


/*
    Function description
*/
function reportGet($id) {
    $reports = array();

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
    Function description
*/
function reportSummary($period) {
    $summary = array();

    $filter = "";
    $query  = "SELECT Class, count(*) AS Count FROM Reports GROUP BY Class";

    $summary = _mysqli_fetch($query);

    return $summary;
}


/*
    Function description
*/
function reportIps($period=14) {
    $summary = array();

    $lastseen = time()-$period*86400;
    $query  = "SELECT DISTINCT Ip FROM Reports WHERE LastSeen > $lastseen";

    if ($ips = _mysqli_fetch($query)) {
        $ret = array();
        foreach ($ips as $ip) $ret[] = $ip['Ip'];
        return $ret;
    }

    return false;
}


/*
    Function description
*/
function reportMerge() {

}


/*
    Function description
*/
function reportHandled() {

}


/*
    Function description
*/
function reportNotified($ticket) {
    // Subfunction for reportNotification which can be called when a notifier
    // successfully send out the notification to mark the ticket as notified
    //
    // Set the notifyCount + 1, Set the LastNotifyReportCount to ReportCount
    $query = "Update Reports SET LastNotifyReportCount = ReportCount, NotifiedCount = NotifiedCount+1 WHERE 1 AND ID = '${ticket}'";
    _mysqli_query($query, "");
}


/*
    Function description
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
    $query = "SELECT * FROM Reports WHERE 1 ";

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

    } elseif (isset($filter['Days'])) {
        $from = time()-(86400*$filter['Days']);
        $query .= "AND LastSeen >= $from ";

    } elseif (isset($filter['All'])) {
        $query .= "AND AutoNotify = '1' ";

    } else {
        return false;
    }

    foreach(_mysqli_fetch($query) as $id => $row) {
        if(isset($filter['All']) && $row['ReportCount'] == $row['LastNotifyReportCount']) {
            // Already notified, nothing new to report

        } elseif($row['CustomerIgnored'] == 1) {
            // Customer does not want any more notifications from this report

        } elseif(isset($filter['All']) && $row['ReportCount'] != $row['LastNotifyReportCount'] && $row['AutoNotify'] == '1') {
            // Tjek if the customer has the AutoNotify flag AND is not undefined AND the e-mail address is valid

            if ($row['CustomerCode'] != "UNDEF") {
                $data[$row['CustomerCode']][] = $row;
            }
        } else {
            $data[$row['CustomerCode']][] = $row;
        }
    }

    return $data;
}

/*
    Get additional information for a class
*/
function getClassInfo($class) {
    $class_file = APP.'/lib/templates/class/'.preg_replace('/( )+/','_',preg_replace('@/@','',strtolower($class))).'.txt';
    if (file_exists($class_file)) {
        return file_get_contents($class_file);
    } else {
        return false;
    }
}

?>
