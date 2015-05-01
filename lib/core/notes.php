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
* Core notes related functions
*
******************************************************************************/


/*
** Function: reportNoteList
** Parameters: 
**  filter(string): SQL Where Condition
** Returns: 
**  (array): Mysql rows
*/
function reportNoteList($filter) {
    $reports = array();

    $query = "SELECT * FROM Notes WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);

    return $reports;
}


/*
** Function: reportNoteAdd
** Parameters: 
**  submittor(string): Name 
**  ReportID(int): Report ID
**  note(string): The actual note
** Returns: 
**  (int): Mysql insert ID
*/
function reportNoteAdd($submittor, $ReportID, $note) {
    if (NOTES != true || !is_numeric($ReportID)) {
        return false;
    } else {

        // Ignore note if identical note already exists
        $query = "SELECT * FROM Notes WHERE ReportID='".$ReportID."' AND Submittor='".$submittor."' AND Text='".mysql_escape_string(htmlentities($note))."'";
        $result = _mysqli_query($query, "");
        if ($result->num_rows) return true;

        $query = "INSERT INTO Notes (
                                        ReportID, 
                                        Timestamp, 
                                        Submittor, 
                                        Text 
                            ) VALUES (
                                        '".$ReportID."', 
                                        '".time()."', 
                                        '".$submittor."', 
                                        '".mysql_escape_string(htmlentities($note))."'
                            );";

        return _mysqli_query($query, "");
    }
}


/*
** Function: reportNoteDelete
** Parameters: 
**  NoteID(int):
** Returns: 
**  (boolean)
*/
function reportNoteDelete($NoteID) {
    if (NOTES != true || NOTES_DELETABLE != true || !is_numeric($NoteID)) {
        return false;
    } else {
        $query = "DELETE FROM Notes WHERE ID = '${NoteID}';";
        return _mysqli_query($query, "");
    }
}
?>
