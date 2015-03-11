<?php
/*
    Function description
*/
function evidenceStore($sender, $subject, $data) {
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


/*
    Function description
*/
function evidenceLink($evidenceID, $reportID) {
    if(!is_numeric($evidenceID) || !is_numeric($reportID)) {
        return false;
    }

    $query = "INSERT IGNORE INTO EvidenceLinks (EvidenceID, ReportID) VALUES ('${evidenceID}', '${reportID}');";

    $result = _mysqli_query($query);

    return $result;
}


/*
    Function description
*/
function evidenceList($ticket) {
    if(!is_numeric($ticket)) {
        return false;
    }

    $reports = array();

    $query = "SELECT EvidenceLinks.EvidenceID, EvidenceLinks.ReportID, Evidence.ID, Evidence.LastModified, Evidence.Sender, Evidence.Subject ".
             "FROM Evidence, EvidenceLinks WHERE 1 AND EvidenceLinks.EvidenceID = Evidence.ID AND EvidenceLinks.ReportID = '${ticket}';";

    $reports = _mysqli_fetch($query);

    return $reports;
}


/*
    Function description
*/
function evidenceGet($id) {
    $reports = array();

    if(!is_numeric($id)) {
        return false;
    }

    $filter  = "AND ID='${id}'";
    $query   = "SELECT * FROM Evidence WHERE 1 ${filter}";

    $report = _mysqli_fetch($query);

    if (isset($report[0])) {
        return $report[0];
    } else {
        return false;
    }
}
?>
