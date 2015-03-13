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

    $array = array();
    $row = 1;
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			// Skip empty csv rows
			if ($data[0] == NULL) continue;
            if ($row === 1) {
                $headers = $data;
                $magic   = count($data);
            } else {
                $num = count($data);
                if ($num !== $magic) {
                    logger(LOG_ERR, __FUNCTION__ . " The number of cells do not match the header. CSV is either corrupt or incomplete.");

                    return false;
                }

                for ($c=0; $c < $num; $c++) {
                    if ($headers[$c] == "timestamp") {
                        $array[$row][$headers[$c]] = strtotime($data[$c]);
                    } else {
                        $array[$row][$headers[$c]] = $data[$c];
                    }
                }
            }
            $row++;
        }
        fclose($handle);
    }
    return $array;
}
?>
