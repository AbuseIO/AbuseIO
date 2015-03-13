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
* Core configuration related functions
*
******************************************************************************/


/*
** Function: define_configuration
** Parameters: 
**  configFile(string): The file including path which holds the config 
** Returns: Nothing (its sets global DEFINES)
*/
function define_configuration($configFile) {
    if ($ini_array = parse_ini_file($configFile)) {
        foreach ($ini_array as $key => $value) {
    		DEFINE(strtoupper($key), $value);
        }
    } else {
        die("FATAL - Unable to parse ${configFile}");
    }
}
?>
