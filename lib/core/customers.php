<?php
/*
    Function description
*/
function customerCreate() {

}


/*
    Function description
*/
function customerDelete() {

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
function customerList() {

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
