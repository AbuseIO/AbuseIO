<?php
/*
    Function description
*/
function netblockCount($filter) {
    $reports = array();
    $query = "SELECT COUNT(*) as Count FROM Netblocks WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);
    if (!empty($reports[0]['Count'])) return $reports[0]['Count'];
    return 0;
}


/*
    Function description
*/
function netblockAdd($netblock) {
    if (NOTES != true || !is_array($netblock)) {
        return false;
    } else {
        $query = "INSERT INTO Netblocks (
                                        begin_in,
                                        end_in,
                                        CustomerCode
                            ) VALUES (
                                        '".mysql_escape_string($netblock['Begin'])."',
                                        '".mysql_escape_string($netblock['End'])."',
                                        '".mysql_escape_string($netblock['CustomerCode'])."'
                            );";

        return _mysqli_query($query, "");
    }
}

/*
    Function description
*/
function netblockDelete($NetblockID) {
    if (!is_numeric($NetblockID)) {
        return false;
    } else {
        $query = "DELETE FROM Netblocks WHERE ID = '${NetblockID}';";
        return _mysqli_query($query, "");
    }
}

/*
    Function description
*/
function netblockGet() {

}


/*
    Function description
*/
function netblockUpdate() {

}


/*
    Function description
*/
function netblockList($filter) {
    $reports = array();

    $query = "SELECT * FROM Netblocks WHERE 1 ${filter}"; 
    $reports = _mysqli_fetch($query);

    return $reports;
}
?>
