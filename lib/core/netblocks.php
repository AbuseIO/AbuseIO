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
* Core netblock related functions
*
******************************************************************************/


/*
** Function: netblockCount 
** Parameters: 
**  filter(string): SQL WHERE Condition
** Returns: 
**  (int): Mysql row count
*/
function netblockCount($filter) {
    $reports = array();
    $query = "SELECT COUNT(*) as Count FROM Netblocks WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);
    if (!empty($reports[0]['Count'])) return $reports[0]['Count'];
    return 0;
}


/*
** Function: netblockAdd
** Parameters: 
**  netblock(array):
**   begin_in(int):
**   end_in(int):
**   CustomerCode(string):
** Returns: 
**  (int): Mysql insert ID
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
** Function: netblockDelete
** Parameters: 
**  begin_in(int):
**  end_in(int):
** Returns: 
**  (boolean)
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
** Function: netblockGet
** Parameters: 
** Returns: 
*/
function netblockGet() {

}


/*
** Function: netblockUpdate
** Parameters: 
** Returns: 
*/
function netblockUpdate() {

}


/*
** Function: netblockList
** Parameters: 
**  filter(string):
** Returns: 
**  (array): mysql rows
*/
function netblockList($filter) {
    $reports = array();

    $query = "SELECT Netblocks.begin_in, Netblocks.end_in, Netblocks.CustomerCode, Customers.Code, Customers.Name FROM Netblocks LEFT JOIN Customers ON Customers.Code = Netblocks.CustomerCode WHERE 1 ${filter}";
    $reports = _mysqli_fetch($query);

    return $reports;
}
?>
