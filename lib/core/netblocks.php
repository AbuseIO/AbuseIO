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
    if (!is_array($netblock)) {
        return false;
    } else {
        $query = "INSERT INTO Netblocks (
                                        begin_in,
                                        end_in,
                                        CustomerCode
                            ) VALUES (
                                        '".mysql_escape_string($netblock['begin_in'])."',
                                        '".mysql_escape_string($netblock['end_in'])."',
                                        '".mysql_escape_string($netblock['CustomerCode'])."'
                            );";

        return _mysqli_query($query, "");
    }
}

/*
    Function description
*/
function netblockDelete($begin_in, $end_in) {
    if (!is_numeric($begin_in) || !is_numeric($end_in)) {
        return false;

    } else {
        $query = "DELETE FROM Netblocks WHERE begin_in = '${begin_in}' AND end_in = '${end_in}';";

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

    $query = "SELECT Netblocks.begin_in, Netblocks.end_in, Netblocks.CustomerCode, Customers.Code, Customers.Name FROM Netblocks, Customers WHERE 1 AND Customers.Code = Netblocks.CustomerCode ${filter}"; 
    $reports = _mysqli_fetch($query);

    return $reports;
}
?>
