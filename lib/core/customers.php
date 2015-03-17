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
* Core customer related functions
*
******************************************************************************/


/*
** Function: customerCount
** Parameters: 
**  filter(string): SQL WHERE Conditions to be added to the selection
** Returns: 
**  (int): Amount if customers in the database
*/
function customerCount($filter) {
    $reports = array();
    $query = "SELECT COUNT(*) as Count FROM Customers WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);

    if (!empty($reports[0]['Count'])) {
        return $reports[0]['Count'];
    }

    return 0;
}


/*
** Function: customerAdd
** Parameters: 
**  customer(array):
**   [Code](string): The customer ID code
**   [Name](string): The customer name
**   [Contact](string): The customer contacts, comma (,) seperated without spaces
**   [AutoNotify](boolean): Wither this customer may received automatic notifications
** Returns: 
**  (int): Mysql insert ID
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
** Function: customerDelete
** Parameters: 
**  customerCode(string): The customer ID code
** Returns: 
**  (boolean): Mysql query result
*/
function customerDelete($customerCode) {
    if(!is_numeric($customerCode)) {
        return false;
    }

    $query = "DELETE FROM Customers WHERE Code = '${customerCode}';";

    return _mysqli_query($query, "");
}


/*
** Function: customerGet
** Parameters: 
** Returns: 
*/
function customerGet() {

}


/*
** Function: customerUpdate
** Parameters: 
** Returns: 
*/
function customerUpdate() {

}


/*
** Function: customerList
** Parameters: 
**  filter(string): SQL WHERE Conditition
** Returns: 
**  (array): all rows of customers
*/
function customerList($filter) {
    $reports = array();

    $query = "SELECT * FROM Customers WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);

    return $reports;
}


/*
** Function: customerLookupIP
** Parameters: 
**  ip(string): a valid IP adres
** Returns: 
**  (array): 
**   [Code](string): The customer ID code
**   [Name](string): The customer name
**   [Contact](string): The customer contacts, comma (,) seperated without spaces
**   [AutoNotify](boolean): Wither this customer may received automatic notifications
*/
function customerLookupIP($ip) {
    // Local matches are already perferred
    $longip  = ip2long($ip);
    $query   = "SELECT Code, Name, Contact, AutoNotify FROM Netblocks, Customers WHERE 1 AND Netblocks.CustomerCode = Customers.Code AND begin_in <= '${longip}' AND end_in >= '${longip}' ORDER BY begin_in DESC LIMIT 1"; 
    $count   = _mysqli_num_rows($query);
    if ($count === 1) {
        $result = _mysqli_fetch($query);
        $customer = $result[0];
        return $customer;

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


/*
** Function: customerLookupCode
** Parameters:
**  code(string): a customer code
** Returns:
**  (array):
**   [Code](string): The customer ID code
**   [Name](string): The customer name
**   [Contact](string): The customer contacts, comma (,) seperated without spaces
**   [AutoNotify](boolean): Wither this customer may received automatic notifications
*/
function customerLookupCode($code) {
    $customer = array();

    $query = "SELECT Code, Name, Contact, AutoNotify FROM Customers WHERE 1 AND Code='${code}'";
    $result = _mysqli_fetch($query);

    if (is_array($result) && isset($result[0]) && isset($result[0]['Name']) && isset($result[0]['Contact'])) {
        $customer['Code']       = $result[0]['Code'];
        $customer['Name']       = $result[0]['Name'];
        $customer['Contact']    = $result[0]['Contact'];
        $customer['AutoNotify'] = $result[0]['AutoNotify'];

        return $customer;

    } else {
        // If there are no matches then find on the user defined lookup
        if(function_exists('custom_find_customer_by_code')) {
            $custom_find = custom_find_customer_by_code($code);
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
