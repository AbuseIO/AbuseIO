<?PHP
function evidence_store($sender, $subject, $data) {
    $query = "INSERT INTO Evidence (
                                    Sender, 
                                    Subject, 
                                    Data
                         ) VALUES (
                                    '" . mysql_escape_string($sender) . "',
                                    '" . mysql_escape_string($subject) . "',
                                    '" . mysql_escape_string($data) . "'
                                  );";

    $id = _mysqli_query($query);

    return $id;
}

function evidence_link($evidenceID, $reportID) {
    $query = "INSERT IGNORE INTO EvidenceLinks (EvidenceID, ReportID) VALUES ('${evidenceID}', '${reportID}');";

    $result = _mysqli_query($query);

    return $result;
}

function reportAdd($report) {
    // Array should minimally contain $source(string), $ip(string), $class(string), $timestamp(int), $information(array)
    if (!is_array($report)) {
        return false;
    } else {
        extract($report);
    }

    if (!isset($ip) || !isset($source) || !isset($class) || !isset($timestamp)) {
        return false;
    }

    $select = "SELECT * FROM Reports";
    $filter = "WHERE IP='${ip}' AND Source='${source}' AND Class='${class}' AND LastSeen > '".($timestamp-(86400*7))."' ORDER BY LastSeen DESC LIMIT 1;";
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
        } elseif ($row['LastSeen'] > $timestamp) {
            logger(LOG_WARNING, __FUNCTION__ . " by $source ip $ip class $class seen " . date("d-m-Y H:i:s",$timestamp) . " is OBSOLETE!");
            return true;
        } else {
            // TODO when updating not only update timestamp but also match information if there is something new to add
            $update = "UPDATE Reports SET LastSeen='${timestamp}', ReportCount='".($row['ReportCount'] + 1)."'";
            $query  = "${update} ${filter}";
            if (_mysqli_query($query, "")) {
                logger(LOG_DEBUG, __FUNCTION__ . " by $source ip $ip class $class seen " . date("d-m-Y H:i:s",$timestamp) . " is UPDATED");
                return true;
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

function reportList($filter) {
    $reports = array();
    $query = "SELECT * FROM Reports WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);
    return $reports;
}

function reportCount($filter) {
    $reports = array();
    $query = "SELECT COUNT(*) as Count FROM Reports WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);
    if (!empty($reports[0]['Count'])) return $reports[0]['Count'];
    return 0;
}

function reportGet($id) {

    // FIXME: Only 1 evidence row is currently included in the report, while multiple could exist
    $reports = array();

    $filter  = "AND Reports.ID='${id}'";
    $query   = "SELECT Reports.*,Evidence.Data as Evidence FROM Reports,EvidenceLinks,Evidence WHERE Reports.ID=EvidenceLinks.ReportID and EvidenceLinks.EvidenceID=Evidence.ID ${filter}";
    $report = _mysqli_fetch($query);

    if (isset($report[0])) {
        return $report[0];
    } else {
        return false;
    }
}

function reportNoteList($filter) {
    $reports = array();

    $query = "SELECT * FROM Notes WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);

    return $reports;
}

function reportNoteAdd($submittor, $ReportID, $note) {
    if (NOTES != true || !is_numeric($ReportID)) {
        return false;
    } else {
        $query = "INSERT INTO Notes (
                                        ReportID, 
                                        Timestamp, 
                                        Submittor, 
                                        Text 
                            ) VALUES (
                                        '".$ReportID."', 
                                        '".time()."', 
                                        '".$submittor."', 
                                        '".mysql_escape_string($note)."'
                            );";

        return _mysqli_query($query, "");
    }
}

function reportNoteDelete($NoteID) {
    if (NOTES != true || NOTES_DELETABLE != true || !is_numeric($NoteID)) {
        return false;
    } else {
        $query = "DELETE FROM Notes WHERE ID = '${NoteID}';";
        return _mysqli_query($query, "");
    }
}

function reportSummary($period) {
    $summary = array();

    $filter = "";
    $query  = "SELECT Class, count(*) AS Count FROM Reports GROUP BY Class";

    $summary = _mysqli_fetch($query);

    return $summary;
}

function reportMerge() {

}

function reportHandled() {

}

function reportNotified($ticket) {
    // Subfunction for reportNotification which can be called when a notifier
    // successfully send out the notification to mark the ticket as notified
    //
    // Set the notifyCount + 1, Set the LastNotifyReportCount to ReportCount
    $query = "Update Reports SET LastNotifyReportCount = ReportCount, NotifiedCount = NotifiedCount+1 WHERE 1 AND ID = '${ticket}'";
    _mysqli_query($query, "");
}

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

    } elseif (isset($filter['All'])) {
        // Notify everything from anyone (cronable)
        // Doesnt really need filtering does it, but it beats the return

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

function CustomerLookup($ip) {
    // Local matches are already perferred
    $longip  = ip2long($ip);
    $query   = "SELECT Code, Name, Contact, AutoNotify FROM Netblocks, Customers WHERE 1 AND Netblocks.CustomerCode = Customers.Code AND begin_in <= '${longip}' AND end_in >= '${longip}' ORDER BY begin_in DESC"; 
    $count   = _mysqli_num_rows($query);
    if ($count === 1) {
        $result = _mysqli_fetch($query);
        $customer = $result[0];
        return $customer;

    } elseif ($count > 1) {
        // There should never be two duplicates here
        return false;

    } else {
        // If there are no matches then find on the user defined lookup
        if(function_exists('custom_find_customer')) {
            $custom_find = custom_find_customer($ip);
            if($custom_find !== false) {
                return $custom_find;
            }
        }
    }

    //Lookup failed thus we return dummy
    $customer['Code'] = "UNDEF";
    $customer['Name'] = "Undefined customer";
    $customer['Contact'] = "undef@local.isp";
    $customer['AutoNotify'] = 0;

    return $customer;
}
?>
