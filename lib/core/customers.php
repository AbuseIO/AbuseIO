<?php
/*
    Function description
*/
function customerCount($filter) {
    $reports = array();
    $query = "SELECT COUNT(*) as Count FROM Customers WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);
    if (!empty($reports[0]['Count'])) return $reports[0]['Count'];
    return 0;
}


/*
    Function description
*/
function customerAdd($customer) {
    if (!is_array($customer)) {
        return false;
    } else {
        $query = "INSERT INTO Customers (
                                        Code,
                                        Name,
                                        Contact,
                                        AutoNotify
                            ) VALUES (
                                        '".mysql_escape_string($customer['Code'])."',
                                        '".mysql_escape_string($customer['Name'])."',
                                        '".mysql_escape_string($customer['Contact'])."',
                                        '".mysql_escape_string($customer['AutoNotify'])."'
                            );";

        return _mysqli_query($query, "");
    }
}

/*
    Function description
*/
function customerDelete($CustomerCode) {
    $query = "DELETE FROM Customers WHERE Code = '${CustomerCode}';";

    return _mysqli_query($query, "");
}

/*
    Function description
*/
function customerGet() {

}


/*
    Function description
*/
function customerUpdate() {

}


/*
    Function description
*/
function customerList($filter) {
    $reports = array();

    $query = "SELECT * FROM Customers WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);

    return $reports;
}


/*
    Function description
*/
function customerLookup($ip) {
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
        if(function_exists('custom_find_customer_by_ip')) {
            $custom_find = custom_find_customer_by_ip($ip);
            if($custom_find !== false) {
                return $custom_find;
            }
        }
    }

    //Lookup failed thus we return dummy
    $customer['Code'] = "UNDEF";
    $customer['Name'] = "Undefined customer";
    $customer['Contact'] = "";
    $customer['AutoNotify'] = 0;

    return $customer;
}
?>
