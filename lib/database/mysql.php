<?PHP
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
* Core mysql related functions
*
******************************************************************************/


/*
** Function: _mysqli_connect
** Parameters: None
** Returns: A valid mysqli_connection (or false when it doenst)
*/
function _mysqli_connect() {
    if ($mysqli_connection = mysqli_connect(SQL_HOST, SQL_USER, SQL_PASS, SQL_DBNAME)) {
        return $mysqli_connection;
    }
    logger(LOG_ERR,"ERROR - Connection to the database server failed while trying to connect.");
    return false;
}


/*
** Function: _mysqli_query
** Parameters: 
**  query(string): The select in SQL that needs to be executed
**  link(resource): A mysqli_connection resource from _mysqli_connect
** Returns: mysql insert id, result set or false when the query failed
*/
function _mysqli_query($query, $link = "") {
    if(is_string($link)) {
        if (($link = _mysqli_connect())===false) {
            return false;
        }
    }

    if(substr($query,0,6) == "INSERT") {
        mysqli_query($link, $query);
        $result = mysqli_insert_id($link);
    } else {
        $result = mysqli_query($link, $query);
    }

    if (mysqli_errno($link)) {
        logger(LOG_ERR,"Fatal ERROR in MySQL Query ($query), Error:".mysqli_errno($link) . ': ' . mysqli_error($link) . PHP_EOL);
        return false;
    }

    return $result;
}


/*
** Function: _mysqli_fetch
** Parameters: 
**  query(string): The select in SQL that needs to be executed
** Returns: a array with all rows that returned from the SQL select query
*/
function _mysqli_fetch($query) {
    if (($link = _mysqli_connect())===false) {
        return false;
    }
    if ($result = _mysqli_query($query, $link)) {
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        return $return;
    }
    return false;
}


/*
** Function: _mysqli_num_rows
** Parameters:
**  query(string): The select in SQL that needs to be executed
** Returns: an integer with the row count from the SQL select query
*/
function _mysqli_num_rows($query) {
    if (($link = _mysqli_connect())===false) {
        return false;
    }
    if ($result = _mysqli_query($query, $link)) {
        $return = array();
        $return = mysqli_num_rows($result);
        return $return;
    }
    return false;
}
?>
