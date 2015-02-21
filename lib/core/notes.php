<?php
/*
    Function description
*/
function reportNoteList($filter) {
    $reports = array();

    $query = "SELECT * FROM Notes WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);

    return $reports;
}


/*
    Function description
*/
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


/*
    Function description
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
