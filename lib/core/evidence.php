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
* Core evidence related functions
*
******************************************************************************/


/*
** Function: evidenceStore
** Parameters: 
**  sender(string):
**  subject(string):
**  data(string): The raw EML formatted message
** Returns: 
**  (int): mysql insert ID
*/
function evidenceStore($sender, $subject, $data) {
    $query = "INSERT INTO Evidence (
                                    Sender, 
                                    Subject, 
                                    Data
                         ) VALUES (
                                    '" . mysql_escape_string($sender) . "',
                                    '" . substr(mysql_escape_string($subject),0,255) . "',
                                    '" . mysql_escape_string($data) . "'
                                  );";

    $id = _mysqli_query($query);

    return $id;
}


/*
** Function: evidenceLink 
** Parameters: 
**  evidenceID(int):
**  reportID(int):
** Returns: 
**  (int): mysql insert ID
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
** Function: evidenceList
** Parameters: 
**  ticket(int): List all evidence related to a ticket no#
** Returns: 
**  (array): All mysql rows with evidence
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
** Function: evidenceCleanup
** Parameters:
**  (timestamp): Cleans up all old evidence from SQL (NOT! the archive)
** Returns:
**  (boolean): SQL result
*/
function evidenceCleanup($timestamp) {
    if(!is_numeric($timestamp)) {
        return false;
    }

    $reports = array();

    $query = "SELECT ID, LastModified FROM Evidence WHERE 1 AND LastModified < FROM_UNIXTIME('${timestamp}');";

    $evidences = _mysqli_fetch($query);

    foreach($evidences as $evidence) {
        if(_mysqli_query("DELETE FROM EvidenceLinks WHERE EvidenceID = '${evidence['ID']}';")) {
            if(!_mysqli_query("DELETE FROM Evidence WHERE ID = '${evidence['ID']}';")) {
                return false;
            }
        } else {
            return false;
        }
    }

    return true;
}



/*
** Function: evidenceGet
** Parameters: 
**  id(int): 
** Returns: 
**  (array): All mysql rows with evidence
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
