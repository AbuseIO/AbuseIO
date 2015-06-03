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
* Core generic functions
*
******************************************************************************/


/*
** Function: _die
** Parameters:
**  msg(string): Exit message that will be displayed on CLI
**  code(int): Exit value to be given back to the CLI
** Returns: Nothing
*/
function _die($msg, $code) {
    echo $msg;
    exit($code);
}


/*
** Function: valid_date
** Parameters:
**  date(string): a (human) date formatted string that PHP can put into timestamp
** Returns: True when its a valid date string
*/
function valid_date($date) {
    if (date('d-m-Y H:i', strtotime($date)) == $date) {
        return true;
    } else {
        return false;
    }
}


/*
** Function: valid_ip
** Parameters:
**  ip(string): And IP address
** Returns: True when the IP is valid
*/
function valid_ip($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE));
}


/*
** Function: iprange
** Parameters:
**  range_ip(string): The first IP of the range
**  range_ip(cidr): The CIDR notation without the prefex /
** Returns:
**  (array)
**   first_ip(string): First IP of the range in decimal
**   last_ip(string): Last IP of the range in decimal
*/
function iprange($range_ip,$range_cidr) {
    $corr=(pow(2,32)-1)-(pow(2,32-$range_cidr)-1);
    $first=ip2long($range_ip) & ($corr);
    $length=pow(2,32-$range_cidr)-1;

    return array(
               'first_ip'=>$first,
               'last_ip'=>$first+$length
               );
}


/*
** Function: generate_startstop
** Parameters:
**  ranges(array): as [key] => [ip/cidr]
** Returns:
**  (array)
**   [ip/cidr](array)
**    first_ip(string): First IP of the range in decimal
**    last_ip(string): Last IP of the range in decimal
*/
function generate_startstop($ranges) {
    $startstop = array();
    foreach ($ranges as $key => $range) {
        $range = explode("/", $range);
        $startstop[] = iprange($range[0],$range[1]);
    }

    return $startstop;
}


/*
** Function: difference
** Parameters:
**  value1(int): value to match difference with
**  value2(int): value to match difference with
**  offset(int): Threshold when its considered true
** Returns:
*/
function difference($val1, $val2, $offset) {
    if (abs($val1 - $val2) > $offset) {
        return true;
    } else {
        return false;
    }
}
?>
