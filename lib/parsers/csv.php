<?php
function csv_to_array($file) {
    $array = array();
    $row = 1;
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row === 1) {
                $headers = $data;
                $magic   = count($data);
            } else {
                $num = count($data);
                if ($num !== $magic) {
                    // TODO error handle offset unmatched
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
