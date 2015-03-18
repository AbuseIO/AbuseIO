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
******************************************************************************/

/*
** Function: csv_to_array
** Parameters: 
**  file(string): a fill location including path to be read as a CSV
** Returns: An array with all rows with their defined fields
*/
function csv_to_array($file) {
    if(!is_file($file)) {
        logger(LOG_ERR, __FUNCTION__ . " file ${file} used for CSV parsing does not exist");
        return false;
    }

    $data = array();
    
    $csvdata = array_map('str_getcsv', file($file));
    if (count($csvdata) > 1) { // there should be a minimum of 2 lines, column names and one line of data
        $header = array_shift($csvdata);
        if (count($csvdata[0]) !== count($header)) {
            logger(LOG_ERR, __FUNCTION__ . " The number of cells do not match the header. CSV is either corrupt or incomplete.");
            return false;
        }

        foreach($csvdata as $row) {
            if ($row[0] == NULL) continue;
            $data[] = array_combine($header, $row);
        }
        return $data;
    } else {
        // Missing data
        logger(LOG_ERR, __FUNCTION__ . " Incomplete CSV file.");
        return false;
    }
}
?>
